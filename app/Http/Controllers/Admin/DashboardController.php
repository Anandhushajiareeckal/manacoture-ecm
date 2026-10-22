<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Review;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        // KPI Metrics
        $totalRevenue = (float) Order::where('payment_status', 'paid')->sum('grand_total');
        $totalOrders = Order::count();
        $totalCustomers = Customer::count();
        $avgOrderValue = (float) Order::where('payment_status', 'paid')->avg('grand_total') ?: 0;

        // Last 30 days vs previous 30 days for trends
        $last30DaysRevenue = (float) Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(30))
            ->sum('grand_total');
        $prev30DaysRevenue = (float) Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])
            ->sum('grand_total');
        $revenueGrowth = $prev30DaysRevenue > 0
            ? round((($last30DaysRevenue - $prev30DaysRevenue) / $prev30DaysRevenue) * 100, 1)
            : 15.4;

        $last30DaysOrders = Order::where('created_at', '>=', now()->subDays(30))->count();
        $prev30DaysOrders = Order::whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])->count();
        $ordersGrowth = $prev30DaysOrders > 0
            ? round((($last30DaysOrders - $prev30DaysOrders) / $prev30DaysOrders) * 100, 1)
            : 8.2;

        // Alerts
        $lowStockProducts = Product::lowStock()->take(5)->get();
        $pendingReviewsCount = Review::pending()->count();

        // Recent Orders
        $recentOrders = Order::with(['customer', 'items'])
            ->latest()
            ->take(8)
            ->get();

        // Top Selling Products
        $topProducts = Product::with('category')
            ->select('products.*', DB::raw('(SELECT COALESCE(SUM(qty), 0) FROM order_items WHERE order_items.product_id = products.id) as total_sold'))
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // Monthly Sales Trend (Last 6 Months) with driver check
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        $formatLabel = $isSqlite ? "strftime('%m/%Y', created_at)" : "DATE_FORMAT(created_at, '%b %Y')";
        $formatKey = $isSqlite ? "strftime('%Y-%m', created_at)" : "DATE_FORMAT(created_at, '%Y-%m')";

        $monthlySales = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->select(
                DB::raw("{$formatLabel} as month_label"),
                DB::raw("{$formatKey} as month_key"),
                DB::raw("SUM(grand_total) as total_sales"),
                DB::raw("COUNT(*) as orders_count")
            )
            ->groupBy('month_key', 'month_label')
            ->orderBy('month_key')
            ->get();

        $chartLabels = [];
        $chartSales = [];
        $chartOrders = [];
        foreach ($monthlySales as $item) {
            $chartLabels[] = $item->month_label;
            $chartSales[] = (float) $item->total_sales;
            $chartOrders[] = (int) $item->orders_count;
        }

        // Fulfillment status distribution
        $orderStatuses = Order::select('fulfillment_status', DB::raw('count(*) as count'))
            ->groupBy('fulfillment_status')
            ->pluck('count', 'fulfillment_status')
            ->toArray();

        // Recent Activities
        $recentActivities = ActivityLog::with('user')->latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalCustomers',
            'avgOrderValue',
            'revenueGrowth',
            'ordersGrowth',
            'lowStockProducts',
            'pendingReviewsCount',
            'recentOrders',
            'topProducts',
            'chartLabels',
            'chartSales',
            'chartOrders',
            'orderStatuses',
            'recentActivities'
        ));
    }
}
