<x-layouts.app :header="'Sale #' . $sale->reference_no">
    <div class="max-w-3xl">

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-primary-400/40 bg-primary-50 px-3 py-2 text-sm text-primary-600">{{ session('success') }}</div>
        @endif

        <div class="card mb-4 grid grid-cols-2 gap-4 sm:grid-cols-4">
            <div>
                <div class="text-xs text-text-secondary">Customer</div>
                <div class="font-medium">{{ $sale->customer?->name }}</div>
            </div>
            <div>
                <div class="text-xs text-text-secondary">Warehouse</div>
                <div class="font-medium">{{ $sale->warehouse?->name }}</div>
            </div>
            <div>
                <div class="text-xs text-text-secondary">Cashier</div>
                <div class="font-medium">{{ $sale->user?->name }}</div>
            </div>
            <div>
                <div class="text-xs text-text-secondary">Date</div>
                <div class="font-medium">{{ $sale->created_at->format('d M Y, h:i A') }}</div>
            </div>
        </div>

        <div class="card mb-4 overflow-x-auto">
            <table class="table-base">
                <thead><tr><th>Product</th><th class="text-right">Qty</th><th class="text-right">Price</th><th class="text-right">Discount</th><th class="text-right">Tax</th><th class="text-right">Total</th></tr></thead>
                <tbody>
                    @foreach ($sale->lines as $line)
                        <tr>
                            <td>{{ $line->product?->name }}</td>
                            <td class="text-right">{{ $line->qty }}</td>
                            <td class="text-right">₹{{ number_format($line->net_unit_price, 2) }}</td>
                            <td class="text-right">₹{{ number_format($line->discount, 2) }}</td>
                            <td class="text-right">₹{{ number_format($line->tax, 2) }}</td>
                            <td class="text-right font-medium">₹{{ number_format($line->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="card">
                <div class="mb-2 font-medium">Payments</div>
                @forelse ($sale->payments as $payment)
                    <div class="flex justify-between border-b border-border py-1.5 text-sm last:border-0">
                        <span>{{ $payment->paying_method }}</span>
                        <span>₹{{ number_format($payment->amount, 2) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-text-muted">No payments recorded.</p>
                @endforelse
            </div>

            <div class="card space-y-1.5 text-sm">
                <div class="flex justify-between text-text-secondary"><span>Subtotal</span><span>₹{{ number_format($sale->total_price, 2) }}</span></div>
                <div class="flex justify-between text-text-secondary"><span>Discount</span><span>−₹{{ number_format($sale->order_discount ?? 0, 2) }}</span></div>
                <div class="flex justify-between text-text-secondary"><span>Shipping</span><span>₹{{ number_format($sale->shipping_cost ?? 0, 2) }}</span></div>
                <div class="flex justify-between border-t border-border pt-2 text-base font-semibold"><span>Grand total</span><span>₹{{ number_format($sale->grand_total, 2) }}</span></div>
                <div class="flex justify-between text-text-secondary"><span>Paid</span><span>₹{{ number_format($sale->paid_amount, 2) }}</span></div>
                <div class="flex justify-between {{ $sale->due > 0 ? 'text-status-unpaid' : '' }}"><span>Due</span><span>₹{{ number_format($sale->due, 2) }}</span></div>
                <div class="pt-2"><span class="pill-{{ $sale->payment_status }}">{{ ucfirst($sale->payment_status) }}</span></div>
            </div>
        </div>

        <div class="mb-4 flex justify-end">
            <a href="{{ route('sales.receipt', $sale) }}" target="_blank" class="btn-primary">
                Print receipt
            </a>
        </div>

    </div>
</x-layouts.app>
