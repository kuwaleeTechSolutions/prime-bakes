<?php

namespace App\Livewire\ExpenseCategories;

use App\Models\ExpenseCategory;
use Livewire\Component;

class Manager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $name = '';
    public bool $is_active = true;

    public function create(): void
    {
        $this->reset(['editingId', 'name']);
        $this->is_active = true;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $category = ExpenseCategory::findOrFail($id);
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->is_active = $category->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate(['name' => ['required', 'string', 'max:191']]);

        ExpenseCategory::updateOrCreate(['id' => $this->editingId], [
            'code' => $this->editingId ? ExpenseCategory::find($this->editingId)->code : ExpenseCategory::generateCode(),
            'name' => $this->name,
            'is_active' => $this->is_active,
        ]);

        $this->showForm = false;
        session()->flash('success', 'Expense category saved.');
    }

    public function delete(int $id): void
    {
        ExpenseCategory::whereKey($id)->update(['is_active' => false]);
    }

    public function render()
    {
        return view('livewire.expense-categories.manager', [
            'categories' => ExpenseCategory::orderBy('name')->get(),
        ]);
    }
}
