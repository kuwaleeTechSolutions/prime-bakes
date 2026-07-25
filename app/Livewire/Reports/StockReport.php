<?php

namespace App\Livewire\Reports;

use App\Models\Product;
use App\Models\Warehouse;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class StockReport extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public string $warehouseFilter = '';
    public bool $lowStockOnly = false;

    public function updatingSearch() { $this->resetPage(); }
    public function updatingWarehouseFilter() { $this->resetPage(); }

    public function render()
    {
        $products = Product::active()
            ->with(['category', 'stockRows' => fn ($q) => $q->when($this->warehouseFilter, fn ($q2) => $q2->where('warehouse_id', $this->warehouseFilter))])
            ->search($this->search)
            ->orderBy('name')
            ->paginate(20);

        if ($this->lowStockOnly) {
            $products->setCollection(
                $products->getCollection()->filter(fn ($product) => $product->is_low_stock)
            );
        }

        return view('livewire.reports.stock-report', [
            'products' => $products,
            'warehouses' => Warehouse::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}
