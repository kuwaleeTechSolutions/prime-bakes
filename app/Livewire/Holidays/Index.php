<?php

namespace App\Livewire\Holidays;

use App\Models\Holiday;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public bool $showForm = false;
    public string $from_date = '';
    public string $to_date = '';
    public string $note = '';

    public function create(): void
    {
        $this->reset(['from_date', 'to_date', 'note']);
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date', 'after_or_equal:from_date'],
        ]);

        Holiday::create([
            'user_id' => auth()->id(),
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'note' => $this->note ?: null,
            'is_approved' => false,
        ]);

        $this->showForm = false;
        session()->flash('success', 'Leave request submitted.');
    }

    public function approve(int $id): void
    {
        Holiday::whereKey($id)->update(['is_approved' => true]);
    }

    public function reject(int $id): void
    {
        Holiday::whereKey($id)->delete();
    }

    public function render()
    {
        return view('livewire.holidays.index', [
            'holidays' => Holiday::with('user')->latest()->paginate(15),
        ]);
    }
}
