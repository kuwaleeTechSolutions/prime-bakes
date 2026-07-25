<footer class="flex items-center justify-between border-t border-border bg-surface-1 px-5 py-2 text-xs text-text-muted">
    <span>&copy; {{ now()->year }} {{ config('app.name', 'Retailo POS') }}</span>
    <span>v1.0 &middot; Warehouse: {{ session('active_warehouse', 'Main') }}</span>
</footer>
