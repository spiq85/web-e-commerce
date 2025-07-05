<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index() {
        $pendingUsersCount = User::where('is_active', false)->count();
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $totalProductsCount = Product::count();

        return view('admin.dashboard', compact(
            'pendingUsersCount',
            'pendingOrdersCount',
            'totalProductsCount',
        ));
    }
}
