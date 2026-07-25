<?php

namespace App\Livewire\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $categoryFilter = '';

    #[Url]
    public string $brandFilter = '';

    public string $statusFilter = 'active'; // active | inactive | all

    public ?int $confirmingDeleteId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingBrandFilter()
    {
        $this->resetPage();
    }

    public function confirmDelete(int $productId): void
    {
        $this->confirmingDeleteId = $productId;
    }

    public function delete(): void
    {
        if (! $this->confirmingDeleteId) {
            return;
        }

        // Soft-deactivate rather than hard delete — products referenced by historical
        // sales/purchases (product_sales.product_id etc.) shouldn't be removed outright.
        Product::whereKey($this->confirmingDeleteId)->update(['is_active' => false]);

        $this->confirmingDeleteId = null;
        session()->flash('success', 'Product deactivated.');
    }

    public function toggleActive(int $productId): void
    {
        $product = Product::findOrFail($productId);
        $product->update(['is_active' => ! $product->is_active]);
    }

    public function render()
    {
        $products = Product::query()
            ->with(['category', 'brand', 'saleUnit'])
            ->search($this->search)
            ->when($this->categoryFilter, fn ($q) => $q->where('category_id', $this->categoryFilter))
            ->when($this->brandFilter, fn ($q) => $q->where('brand_id', $this->brandFilter))
            ->when($this->statusFilter === 'active', fn ($q) => $q->where('is_active', true))
            ->when($this->statusFilter === 'inactive', fn ($q) => $q->where('is_active', false))
            ->latest()
            ->paginate(15);

        return view('livewire.products.index', [
            'products' => $products,
            'categories' => Category::active()->orderBy('name')->get(),
            'brands' => Brand::active()->orderBy('title')->get(),
        ]);
    }
}
