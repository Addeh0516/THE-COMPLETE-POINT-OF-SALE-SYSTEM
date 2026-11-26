<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();

        return view('dashboard', [
            'productCount' => Product::count(),
            'availableStock' => Product::sum('stock_quantity'),
            'todaySales' => Sale::whereDate('created_at', $today)->count(),
            'todayRevenue' => Sale::whereDate('created_at', $today)->sum('total_price'),
            'activeStaff' => User::count(),
            'lowStockProducts' => Product::where('stock_quantity', '<=', 5)->orderBy('stock_quantity')->take(5)->get(),
            'recentSales' => Sale::with(['product', 'customer', 'staff'])->latest()->take(6)->get(),
        ]);
    }
}
