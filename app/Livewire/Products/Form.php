<?php

namespace App\Livewire\Products;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tax;
use App\Models\Unit;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Form extends Component
{
    #[Locked]
    public ?int $productId = null;

    public string $name = '';
    public string $code = '';
    public string $type = 'standard';
    public ?int $category_id = null;
    public ?int $brand_id = null;
    public ?int $unit_id = null;
    public ?int $purchase_unit_id = null;
    public ?int $sale_unit_id = null;
    public ?float $cost = null;
    public ?float $price = null;
    public ?float $alert_quantity = null;
    public ?int $tax_id = null;
    public int $tax_method = 1; // 1 = inclusive, 2 = exclusive
    public bool $is_batch = false;
    public bool $is_variant = false;
    public bool $is_active = true;

    public function mount(?Product $product = null): void
    {
        if ($product?->exists) {
            $this->productId = $product->id;
            $this->fill($product->only([
                'name', 'code', 'type', 'category_id', 'brand_id', 'unit_id',
                'purchase_unit_id', 'sale_unit_id', 'cost', 'price',
                'alert_quantity', 'tax_id', 'tax_method', 'is_batch', 'is_variant', 'is_active',
            ]));
        }
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:191'],
            'code' => ['required', 'string', 'max:191', 'unique:products,code,' . $this->productId],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'unit_id' => ['required', 'exists:units,id'],
            'purchase_unit_id' => ['required', 'exists:units,id'],
            'sale_unit_id' => ['required', 'exists:units,id'],
            'cost' => ['required', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'alert_quantity' => ['nullable', 'numeric', 'min:0'],
            'tax_id' => ['nullable', 'exists:taxes,id'],
        ];
    }

    public function save()
    {
        $validated = $this->validate();
        $validated['barcode_symbology'] = 'CODE128';
        $validated['is_batch'] = $this->is_batch;
        $validated['is_variant'] = $this->is_variant;
        $validated['is_active'] = $this->is_active;
        $validated['tax_method'] = $this->tax_method;

        $product = Product::updateOrCreate(['id' => $this->productId], $validated);

        session()->flash('success', $this->productId ? 'Product updated.' : 'Product created.');

        return redirect()->route('products.index');
    }

    public function render()
    {
        return view('livewire.products.form', [
            'categories' => Category::active()->orderBy('name')->get(),
            'brands' => Brand::active()->orderBy('title')->get(),
            'units' => Unit::active()->orderBy('unit_name')->get(),
            'taxes' => Tax::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}
