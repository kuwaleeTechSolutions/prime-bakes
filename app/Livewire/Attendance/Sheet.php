<?php

namespace App\Livewire\Attendance;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\HrmSetting;
use Livewire\Component;

class Sheet extends Component
{
    public string $date;

    // Each: employee_id, name, checkin, checkout, status, note
    public array $rows = [];

    public function mount(): void
    {
        $this->date = now()->toDateString();
        $this->loadSheet();
    }

    public function updatedDate(): void
    {
        $this->loadSheet();
    }

    protected function loadSheet(): void
    {
        $settings = HrmSetting::current();

        $existing = Attendance::where('date', $this->date)->get()->keyBy('employee_id');

        $this->rows = Employee::active()->orderBy('name')->get()->map(function ($employee) use ($existing, $settings) {
            $record = $existing->get($employee->id);

            return [
                'employee_id' => $employee->id,
                'name' => $employee->name,
                'checkin' => $record->checkin ?? $settings->checkin,
                'checkout' => $record->checkout ?? $settings->checkout,
                'status' => $record->status ?? 'present',
                'note' => $record->note ?? '',
            ];
        })->toArray();
    }

    public function save(): void
    {
        foreach ($this->rows as $row) {
            Attendance::updateOrCreate(
                ['date' => $this->date, 'employee_id' => $row['employee_id']],
                [
                    'user_id' => auth()->id(),
                    'checkin' => $row['checkin'],
                    'checkout' => $row['checkout'],
                    'status' => $row['status'],
                    'note' => $row['note'] ?: null,
                ]
            );
        }

        session()->flash('success', 'Attendance saved for ' . \Illuminate\Support\Carbon::parse($this->date)->format('d M Y') . '.');
    }

    public function render()
    {
        return view('livewire.attendance.sheet');
    }
}
