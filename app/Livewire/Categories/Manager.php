<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Component;

class Manager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $name = '';
    public ?int $parent_id = null;
    public bool $is_active = true;

    public function create(): void
    {
        $this->reset(['editingId', 'name', 'parent_id']);
        $this->is_active = true;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $category = Category::findOrFail($id);
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->parent_id = $category->parent_id;
        $this->is_active = $category->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:191'],
            'parent_id' => ['nullable', 'exists:categories,id', 'different:editingId'],
        ]);

        Category::updateOrCreate(['id' => $this->editingId], [
            'name' => $this->name,
            'parent_id' => $this->parent_id,
            'is_active' => $this->is_active,
        ]);

        $this->showForm = false;
        session()->flash('success', 'Category saved.');
    }

    public function delete(int $id): void
    {
        // Deactivate rather than hard-delete — products.category_id has no cascade,
        // so removing a category outright would orphan any product still using it.
        Category::whereKey($id)->update(['is_active' => false]);
    }

    public function render()
    {
        return view('livewire.categories.manager', [
            'categories' => Category::with('parent')->orderBy('name')->get(),
            'parentOptions' => Category::whereNull('parent_id')->orderBy('name')->get(),
        ]);
    }
}
