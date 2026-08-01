<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Receipt #{{ $sale->reference_no }}</title>
<style>
    @page { size: 80mm auto; margin: 0; }

    * { box-sizing: border-box; }
    body {
        width: 80mm;
        margin: 0 auto;
        padding: 3mm;
        font-family: 'Consolas', 'Courier New', monospace;
        font-size: 11px;
        line-height: 1.4;
        color: #000;
    }

    .center { text-align: center; }
    .right { text-align: right; }
    .bold { font-weight: 700; }
    .store-name { font-size: 15px; font-weight: 700; }
    .small { font-size: 9px; color: #333; }

    .rule { border-top: 1px dashed #000; margin: 6px 0; }

    table { width: 100%; border-collapse: collapse; font-size: 10.5px; }
    th { text-align: left; border-bottom: 1px solid #000; padding-bottom: 2px; font-size: 10px; }
    td { padding: 2px 0; vertical-align: top; }
    .qty-col { width: 10mm; }
    .amt-col { width: 16mm; text-align: right; }

    .totals td { padding: 1px 0; }
    .grand-total { font-size: 13px; font-weight: 700; border-top: 1px solid #000; padding-top: 3px; }

    .no-print { margin-top: 10px; }
    .no-print button {
        width: 100%; padding: 8px; font-size: 13px; margin-bottom: 6px;
        border: 1px solid #999; background: #f2f2f2; border-radius: 4px;
    }

    @media print {
        .no-print { display: none; }
        body { width: auto; }
    }
</style>
</head>
<body>

    <div class="center">
        <div class="store-name">{{ $sale->warehouse?->name ?? config('app.name') }}</div>
        @if ($sale->warehouse?->address)
            <div class="small">{{ $sale->warehouse->address }}</div>
        @endif
        @if ($sale->warehouse?->phone)
            <div class="small">Ph: {{ $sale->warehouse->phone }}</div>
        @endif
    </div>

    <div class="rule"></div>

    <div>Invoice: <span class="bold">#{{ $sale->reference_no }}</span></div>
    <div>Date: {{ ($sale->sale_date ?? $sale->created_at)->format('d-m-Y') }} {{ $sale->created_at->format('h:i A') }}</div>
    <div>Cashier: {{ $sale->user?->name }}</div>
    <div>Customer: {{ $sale->customer?->name ?? 'Walk-in' }}</div>

    <div class="rule"></div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="qty-col right">Qty</th>
                <th class="amt-col">Amt</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->lines as $line)
                <tr>
                    <td>{{ $line->product?->name }}</td>
                    <td class="qty-col right">{{ rtrim(rtrim(number_format($line->qty, 2), '0'), '.') }}</td>
                    <td class="amt-col">{{ number_format($line->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="rule"></div>

    <table class="totals">
        <tr><td>Subtotal</td><td class="right">{{ number_format($sale->total_price, 2) }}</td></tr>
        @if ($sale->order_discount > 0)
            <tr><td>Discount</td><td class="right">-{{ number_format($sale->order_discount, 2) }}</td></tr>
        @endif
        @if ($sale->total_tax > 0)
            <tr><td>Tax</td><td class="right">{{ number_format($sale->total_tax, 2) }}</td></tr>
        @endif
        @if ($sale->shipping_cost > 0)
            <tr><td>Shipping</td><td class="right">{{ number_format($sale->shipping_cost, 2) }}</td></tr>
        @endif
        <tr class="grand-total"><td>TOTAL</td><td class="right">{{ number_format($sale->grand_total, 2) }}</td></tr>
    </table>

    <div class="rule"></div>

    @foreach ($sale->payments as $payment)
        <div>{{ $payment->paying_method }}: {{ number_format($payment->amount, 2) }}</div>
        @if ($payment->change > 0)
            <div>Change: {{ number_format($payment->change, 2) }}</div>
        @endif
    @endforeach

    @if ($sale->due > 0)
        <div class="bold">Balance due: {{ number_format($sale->due, 2) }}</div>
    @endif

    <div class="rule"></div>

    <div class="center small">
        Thank you for shopping with us!<br>
        Please visit again.
    </div>

    <div class="no-print">
        <button onclick="window.print()">Print receipt</button>
        <button onclick="window.close()">Close</button>
    </div>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>

</body>
</html>
