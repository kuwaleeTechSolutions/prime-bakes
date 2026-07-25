<x-layouts.app :header="'HRM'">
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
        <a href="{{ route('employees.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Employees</div>
            <div class="mt-1 text-xs text-text-secondary">Staff records &amp; salaries</div>
        </a>
        <a href="{{ route('departments.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Departments</div>
            <div class="mt-1 text-xs text-text-secondary">Organize employees by team</div>
        </a>
        <a href="{{ route('attendance.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Attendance</div>
            <div class="mt-1 text-xs text-text-secondary">Daily check-in/out sheet</div>
        </a>
        <a href="{{ route('holidays.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Holidays</div>
            <div class="mt-1 text-xs text-text-secondary">Leave requests &amp; approvals</div>
        </a>
        <a href="{{ route('payroll.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Payroll</div>
            <div class="mt-1 text-xs text-text-secondary">Pay salaries against an account</div>
        </a>
    </div>
</x-layouts.app>
