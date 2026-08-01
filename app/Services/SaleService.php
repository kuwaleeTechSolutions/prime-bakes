<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

/**
 * Single write path for completing a sale — the Sales-side counterpart to
 * StockService. POS\Terminal (and, later, any "create sale manually" screen)
 * should call checkout() rather than creating Sale/ProductSale/Payment rows
 * inline, so stock decrement + payment recording + customer loyalty updates
 * stay atomic and consistent everywhere a sale can be created.
 */
class SaleService
{
    public function __construct(
        protected StockService $stock,
    ) {}

    /**
     * @param array $lines Each: ['product_id','qty','sale_unit_id','net_unit_price','discount','tax_rate']
     * @param array $payment ['account_id','paying_method','amount','used_points' => 0]
     *
     * @throws \RuntimeException if any line doesn't have enough stock.
     */
    public function checkout(
        int $warehouseId,
        int $customerId,
        ?int $billerId,
        ?int $cashRegisterId,
        int $userId,
        array $lines,
        array $payment,
        float $orderDiscount = 0,
        ?string $orderDiscountType = 'Flat',
        float $shippingCost = 0,
        ?string $saleNote = null,
        ?string $saleDate = null,
    ): Sale {
        $saleDate ??= now()->toDateString();

        // Fail fast, before writing anything, if any line is short on stock.
        foreach ($lines as $line) {
            $available = $this->stock->stockOf($line['product_id'], $warehouseId);
            if ($available < $line['qty']) {
                throw new \RuntimeException("Insufficient stock for product {$line['product_id']} (have {$available}, need {$line['qty']}).");
            }
        }

        return DB::transaction(function () use (
            $warehouseId, $customerId, $billerId, $cashRegisterId, $userId,
            $lines, $payment, $orderDiscount, $orderDiscountType, $shippingCost, $saleNote, $saleDate
        ) {
            $lineTotals = array_map(fn ($l) => round(
                $l['qty'] * $l['net_unit_price'] - $l['discount']
                + ($l['qty'] * $l['net_unit_price'] - $l['discount']) * ($l['tax_rate'] / 100),
                2
            ), $lines);

            $subtotal = array_sum($lineTotals);
            $grandTotal = round(max($subtotal + $shippingCost - $orderDiscount, 0), 2);

            $sale = Sale::create([
                'reference_no' => Sale::generateReferenceNo(),
                'user_id' => $userId,
                'cash_register_id' => $cashRegisterId,
                'customer_id' => $customerId,
                'warehouse_id' => $warehouseId,
                'biller_id' => $billerId,
                'sale_date' => $saleDate,
                'item' => count($lines),
                'total_qty' => array_sum(array_column($lines, 'qty')),
                'total_discount' => array_sum(array_column($lines, 'discount')),
                'total_tax' => array_sum(array_map(fn ($l) => ($l['qty'] * $l['net_unit_price'] - $l['discount']) * ($l['tax_rate'] / 100), $lines)),
                'total_price' => $subtotal,
                'grand_total' => $grandTotal,
                'order_discount_type' => $orderDiscountType,
                'order_discount' => $orderDiscount,
                'shipping_cost' => $shippingCost,
                'sale_status' => 'completed',
                'payment_status' => $payment['amount'] >= $grandTotal ? 'paid' : ($payment['amount'] > 0 ? 'partial' : 'unpaid'),
                'paid_amount' => $payment['amount'],
                'sale_note' => $saleNote,
            ]);

            foreach ($lines as $index => $line) {
                $sale->lines()->create([
                    'product_id' => $line['product_id'],
                    'qty' => $line['qty'],
                    'sale_unit_id' => $line['sale_unit_id'],
                    'net_unit_price' => $line['net_unit_price'],
                    'discount' => $line['discount'],
                    'tax_rate' => $line['tax_rate'],
                    'tax' => round(($line['qty'] * $line['net_unit_price'] - $line['discount']) * ($line['tax_rate'] / 100), 2),
                    'total' => $lineTotals[$index],
                ]);

                $this->stock->decrement($line['product_id'], $warehouseId, $line['qty']);
            }

            if ($payment['amount'] > 0) {
                $change = max($payment['tendered'] ?? $payment['amount'] - $grandTotal, 0);

                Payment::create([
                    'payment_reference' => Payment::generateReference(),
                    'user_id' => $userId,
                    'sale_id' => $sale->id,
                    'cash_register_id' => $cashRegisterId,
                    'account_id' => $payment['account_id'],
                    'amount' => $payment['amount'],
                    'used_points' => $payment['used_points'] ?? null,
                    'change' => $change,
                    'paying_method' => $payment['paying_method'],
                ]);

                Account::find($payment['account_id'])?->credit($payment['amount']);
            }

            // Loyalty: 1 point per ₹100 spent — a simple, editable default.
            $customer = Customer::find($customerId);
            $customer?->addPoints(floor($grandTotal / 100));
            $customer?->recordExpense($grandTotal);

            if (($payment['used_points'] ?? 0) > 0) {
                $customer?->redeemPoints($payment['used_points']);
            }

            return $sale->fresh(['lines', 'payments']);
        });
    }
}
