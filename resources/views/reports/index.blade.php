<x-layouts.app :header="'Reports'">
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
        <a href="{{ route('reports.sales') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Sales report</div>
            <div class="mt-1 text-xs text-text-secondary">Revenue, collections &amp; outstanding</div>
        </a>
        <a href="{{ route('reports.purchases') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Purchase report</div>
            <div class="mt-1 text-xs text-text-secondary">Spend by supplier &amp; warehouse</div>
        </a>
        <a href="{{ route('reports.profit-loss') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Profit &amp; loss</div>
            <div class="mt-1 text-xs text-text-secondary">Revenue minus COGS, expenses &amp; payroll</div>
        </a>
        <a href="{{ route('reports.stock') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Stock report</div>
            <div class="mt-1 text-xs text-text-secondary">Current levels &amp; low-stock alerts</div>
        </a>
        <a href="{{ route('reports.due') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Due report</div>
            <div class="mt-1 text-xs text-text-secondary">Customers owing you, suppliers you owe</div>
        </a>
        <a href="{{ route('reports.expenses') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Expense report</div>
            <div class="mt-1 text-xs text-text-secondary">Spending broken down by category</div>
        </a>
        <a href="{{ route('reports.tax') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Tax report</div>
            <div class="mt-1 text-xs text-text-secondary">Tax collected vs paid</div>
        </a>
        <a href="{{ route('reports.register') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Register report</div>
            <div class="mt-1 text-xs text-text-secondary">Cash register session history</div>
        </a>
    </div>
</x-layouts.app>
