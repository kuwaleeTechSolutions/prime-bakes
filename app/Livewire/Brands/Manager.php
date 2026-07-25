<?php

namespace App\Livewire\Brands;

use App\Models\Brand;
use Livewire\Component;

class Manager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $title = '';
    public bool $is_active = true;

    public function create(): void
    {
        $this->reset(['editingId', 'title']);
        $this->is_active = true;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $brand = Brand::findOrFail($id);
        $this->editingId = $brand->id;
        $this->title = $brand->title;
        $this->is_active = $brand->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate(['title' => ['required', 'string', 'max:191']]);

        Brand::updateOrCreate(['id' => $this->editingId], [
            'title' => $this->title,
            'is_active' => $this->is_active,
        ]);

        $this->showForm = false;
        session()->flash('success', 'Brand saved.');
    }

    public function delete(int $id): void
    {
        Brand::whereKey($id)->update(['is_active' => false]);
    }

    public function render()
    {
        return view('livewire.brands.manager', [
            'brands' => Brand::orderBy('title')->get(),
        ]);
    }
}
