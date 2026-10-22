<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Product;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $orders = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->get();

        $totalSales = $orders->sum('grand_total');
        $totalOrdersCount = $orders->count();
        $totalTax = $orders->sum('tax_total');
        $totalShipping = $orders->sum('shipping_total');
        $totalDiscounts = $orders->sum('discount_total');

        // Sales by payment method
        $salesByPayment = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select('payment_method', DB::raw('SUM(grand_total) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->get();

        return view('admin.reports.sales', compact(
            'startDate',
            'endDate',
            'totalSales',
            'totalOrdersCount',
            'totalTax',
            'totalShipping',
            'totalDiscounts',
            'salesByPayment'
        ));
    }

    public function exportOrders(Request $request)
    {
        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Order Number', 'Date', 'Customer Name', 'Customer Email', 'Items Count', 'Payment Method', 'Payment Status', 'Fulfillment Status', 'Grand Total']);

            Order::with('customer')->chunk(100, function ($orders) use ($handle) {
                foreach ($orders as $order) {
                    fputcsv($handle, [
                        $order->order_number,
                        $order->created_at->toDateTimeString(),
                        $order->customer_name,
                        $order->customer?->email ?? 'N/A',
                        $order->items()->count(),
                        $order->payment_method,
                        $order->payment_status,
                        $order->fulfillment_status,
                        $order->grand_total,
                    ]);
                }
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="orders_export_' . date('Y-m-d') . '.csv"');

        return $response;
    }

    public function exportProducts()
    {
        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'SKU', 'Name', 'Category', 'Brand', 'Price', 'Cost Price', 'Stock Qty', 'Status', 'Is Featured']);

            Product::with(['category', 'brand'])->chunk(100, function ($products) use ($handle) {
                foreach ($products as $prod) {
                    fputcsv($handle, [
                        $prod->id,
                        $prod->sku,
                        $prod->name,
                        $prod->category?->name ?? 'None',
                        $prod->brand?->name ?? 'None',
                        $prod->price,
                        $prod->cost_price ?? 0,
                        $prod->stock_qty,
                        $prod->status,
                        $prod->is_featured ? 'Yes' : 'No',
                    ]);
                }
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="products_export_' . date('Y-m-d') . '.csv"');

        return $response;
    }
}
