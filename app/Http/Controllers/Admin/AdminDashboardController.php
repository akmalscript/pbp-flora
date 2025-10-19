<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $completedStatuses = ['completed', 'delivered'];

        $completedOrdersQuery = Order::query()->whereIn('status', $completedStatuses);

        $totalRevenue = (clone $completedOrdersQuery)->sum('total');
        $completedOrdersCount = (clone $completedOrdersQuery)->count();

        $totalOrdersCount = Order::query()->count();
        $pendingOrdersCount = Order::query()->whereNotIn('status', $completedStatuses)->count();

    $totalProductStock = Product::query()->sum('stock');
    $lowStockThreshold = 5;
    $lowStockProductCount = Product::query()->where('stock', '<=', $lowStockThreshold)->count();

        $recentOrders = Order::query()
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        $topSellingProducts = OrderItem::query()
            ->select('order_items.product_id', 'products.name as product_name')
            ->selectRaw('SUM(order_items.quantity) as total_quantity')
            ->selectRaw('SUM(order_items.sub_total) as total_revenue')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereIn('orders.status', $completedStatuses)
            ->groupBy('order_items.product_id', 'products.name')
            ->orderByDesc('total_quantity')
            ->take(3)
            ->get();

        $now = Carbon::now();
        $currentMonthStart = $now->copy()->startOfMonth();
        $currentMonthEnd = $now->copy()->endOfMonth();
        $previousMonthStart = $currentMonthStart->copy()->subMonth()->startOfMonth();
        $previousMonthEnd = $currentMonthStart->copy()->subMonth()->endOfMonth();

        $currentMonthRevenue = Order::query()
            ->whereIn('status', $completedStatuses)
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('total');

        $previousMonthRevenue = Order::query()
            ->whereIn('status', $completedStatuses)
            ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
            ->sum('total');

        $growthPercentage = null;
        if ($previousMonthRevenue > 0) {
            $growthPercentage = (($currentMonthRevenue - $previousMonthRevenue) / $previousMonthRevenue) * 100;
        }

        return view('admindashboard', [
            'salesSummary' => [
                'totalRevenue' => $totalRevenue,
                'ordersCount' => $completedOrdersCount,
                'growthPercentage' => $growthPercentage,
            ],
            'orderStats' => [
                'total' => $totalOrdersCount,
                'pending' => $pendingOrdersCount,
            ],
            'inventory' => [
                'totalStock' => $totalProductStock,
                'lowStockCount' => $lowStockProductCount,
                'lowStockThreshold' => $lowStockThreshold,
            ],
            'recentOrders' => $recentOrders,
            'topSellingProducts' => $topSellingProducts,
        ]);
    }
}
