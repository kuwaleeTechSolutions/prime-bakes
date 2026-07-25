<x-layouts.app :header="'Stock'">
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <a href="{{ route('transfers.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Transfers</div>
            <div class="mt-1 text-xs text-text-secondary">Move stock between warehouses</div>
        </a>
        <a href="{{ route('adjustments.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Adjustments</div>
            <div class="mt-1 text-xs text-text-secondary">Correct stock counts manually</div>
        </a>
        <a href="{{ route('damages.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Damaged products</div>
            <div class="mt-1 text-xs text-text-secondary">Write off damaged/expired stock</div>
        </a>
        <a href="{{ route('stock-counts.index') }}" class="card hover:bg-surface-3">
            <div class="font-medium">Stock count</div>
            <div class="mt-1 text-xs text-text-secondary">Physical inventory reconciliation</div>
        </a>
    </div>
</x-layouts.app>