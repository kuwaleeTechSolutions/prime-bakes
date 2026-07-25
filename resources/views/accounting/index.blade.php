<x-layouts.app :header="'Accounting'">
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
        <a href="{{ route('accounts.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Chart of accounts</div>
            <div class="mt-1 text-xs text-text-secondary">Cash, bank, UPI, card accounts &amp; balances</div>
        </a>
        <a href="{{ route('expenses.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Expenses</div>
            <div class="mt-1 text-xs text-text-secondary">Log operational spending by category</div>
        </a>
        <a href="{{ route('expense-categories.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Expense categories</div>
            <div class="mt-1 text-xs text-text-secondary">Organize expenses for reporting</div>
        </a>
        <a href="{{ route('money-transfers.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Money transfers</div>
            <div class="mt-1 text-xs text-text-secondary">Move funds between accounts</div>
        </a>
        <a href="{{ route('deposits.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Deposits</div>
            <div class="mt-1 text-xs text-text-secondary">Customer wallet top-ups</div>
        </a>
        <a href="{{ route('gift-cards.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Gift cards</div>
            <div class="mt-1 text-xs text-text-secondary">Issue, recharge &amp; track balances</div>
        </a>
    </div>
</x-layouts.app>
