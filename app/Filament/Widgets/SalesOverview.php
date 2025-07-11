<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;

class SalesOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Total penjualan bulan ini (status confirmed)
        $currentMonthSales = Order::where('status', 'confirmed')
            ->where('created_at', '>=', $currentMonth)
            ->sum('total_price');

        // Total penjualan bulan lalu (status confirmed)
        $lastMonthSales = Order::where('status', 'confirmed')
            ->where('created_at', '>=', $lastMonth)
            ->where('created_at', '<', $currentMonth)
            ->sum('total_price');

        $salesChange = $lastMonthSales > 0
            ? (($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100
            : 0;

        // Jumlah pesanan aktif/pending
        $activeOrders = Order::where('status', 'pending')->count();

        // Pesanan bulan ini vs bulan lalu
        $currentMonthOrders = Order::where('created_at', '>=', $currentMonth)->count();
        $lastMonthOrders = Order::where('created_at', '>=', $lastMonth)
            ->where('created_at', '<', $currentMonth)
            ->count();

        $ordersChange = $lastMonthOrders > 0
            ? (($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100
            : 0;

        // Produk yang paling sering dipesan (JSON)
        $orders = Order::where('status', 'confirmed')->get();
        $productCounts = [];

        foreach ($orders as $order) {
            $products = json_decode($order->products, true);

            if (is_array($products)) {
                foreach ($products as $product) {
                    if (!isset($productCounts[$product['name']])) {
                        $productCounts[$product['name']] = 0;
                    }
                    $productCounts[$product['name']] += (int) $product['quantity'];
                }
            }
        }

        arsort($productCounts);
        $mostOrderedProduct = array_key_first($productCounts);
        $mostOrderedCount = $mostOrderedProduct ? $productCounts[$mostOrderedProduct] : null;

        $totalCategories = Category::count();
        $totalProducts = Product::count();

        return [
            Stat::make('Total Penjualan Bulan Ini', 'Rp ' . number_format($currentMonthSales, 0, ',', '.'))
                ->description($salesChange >= 0 ? '+' . number_format($salesChange, 1) . '% dari bulan lalu' : number_format($salesChange, 1) . '% dari bulan lalu')
                ->descriptionIcon($salesChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($salesChange >= 0 ? 'success' : 'danger')
                ->chart([
                    $lastMonthSales,
                    $currentMonthSales
                ]),

            Stat::make('Pesanan Pending', $activeOrders)
                ->description('Menunggu konfirmasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Pesanan Bulan Ini', $currentMonthOrders)
                ->description($ordersChange >= 0 ? '+' . number_format($ordersChange, 1) . '% dari bulan lalu' : number_format($ordersChange, 1) . '% dari bulan lalu')
                ->descriptionIcon($ordersChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($ordersChange >= 0 ? 'success' : 'danger')
                ->chart([
                    $lastMonthOrders,
                    $currentMonthOrders
                ]),

            Stat::make('Produk Terfavorit', $mostOrderedProduct ?? 'Belum ada data')
                ->description($mostOrderedCount ? "Dipesan {$mostOrderedCount}x" : 'Belum ada pesanan')
                ->descriptionIcon('heroicon-m-star')
                ->color('info'),

            Stat::make('Total Kategori', $totalCategories)
                ->description($totalProducts . ' total produk')
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->color('success'),
        ];
    }
}
