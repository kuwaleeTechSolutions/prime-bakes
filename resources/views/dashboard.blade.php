<x-layouts.app :header="'Dashboard'">

    {{-- Stat cards --}}
    <div class="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <div class="stat-card bg-primary-50">
            <div class="text-xs text-text-secondary">Today's sales</div>
            <div class="mt-1 text-xl font-semibold">₹{{ number_format($todaySales ?? 48260) }}</div>
        </div>
        <div class="stat-card bg-secondary-50">
            <div class="text-xs text-text-secondary">Today's purchases</div>
            <div class="mt-1 text-xl font-semibold">₹{{ number_format($todayPurchases ?? 12400) }}</div>
        </div>
        <div class="stat-card bg-red-50">
            <div class="text-xs text-text-secondary">Low stock items</div>
            <div class="mt-1 text-xl font-semibold">{{ $lowStockCount ?? 7 }}</div>
        </div>
        <div class="stat-card border border-border bg-surface-1">
            <div class="text-xs text-text-secondary">Cash in register</div>
            <div class="mt-1 text-xl font-semibold">₹{{ number_format($cashInRegister ?? 6850) }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

        {{-- Recent sales --}}
        <div class="card lg:col-span-2">
            <div class="mb-3 font-medium">Recent sales</div>
            <table class="table-base">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse (($recentSales ?? []) as $sale)
                        <tr>
                            <td>#{{ $sale->reference_no }}</td>
                            <td>{{ $sale->customer->name ?? 'Walk-in' }}</td>
                            <td>₹{{ number_format($sale->grand_total, 2) }}</td>
                            <td><span class="pill-{{ $sale->payment_status }}">{{ ucfirst($sale->payment_status) }}</span></td>
                        </tr>
                    @empty
                        {{-- Placeholder rows for preview --}}
                        <tr><td>#INV-1042</td><td>Walk-in</td><td>₹585.00</td><td><span class="pill-paid">Paid</span></td></tr>
                        <tr><td>#INV-1041</td><td>Rahul Sharma</td><td>₹2,140.00</td><td><span class="pill-paid">Paid</span></td></tr>
                        <tr><td>#INV-1040</td><td>Priya Nair</td><td>₹960.00</td><td><span class="pill-partial">Partial</span></td></tr>
                        <tr><td>#INV-1039</td><td>Walk-in</td><td>₹310.00</td><td><span class="pill-paid">Paid</span></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Low stock alerts --}}
        <div class="card">
            <div class="mb-3 font-medium">Low stock alerts</div>
            <div class="flex flex-col gap-2.5 text-sm">
                @forelse (($lowStockProducts ?? []) as $product)
                    <div class="flex items-center justify-between">
                        <span>{{ $product->name }}</span>
                        <span class="pill-unpaid">{{ $product->qty }} left</span>
                    </div>
                @empty
                    <div class="flex items-center justify-between"><span>Sunflower oil 1L</span><span class="pill-unpaid">4 left</span></div>
                    <div class="flex items-center justify-between"><span>Multigrain bread</span><span class="pill-unpaid">2 left</span></div>
                    <div class="flex items-center justify-between"><span>Basmati rice 5kg</span><span class="pill-unpaid">6 left</span></div>
                @endforelse
            </div>
        </div>

    </div>

</x-layouts.app>
