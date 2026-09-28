<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
class DashboardController extends Controller
{
    public function index()
    {
        $data = [];

        // Total de usuarios registrados
        $data['total_users'] = User::count();

        // Usuarios registrados recientemente
        $data['recent_users'] = User::latest()
            ->take(5)
            ->get();

        // Total de productos registrados
        $data['total_products'] = Product::count();

        // Total de unidades ingresadas durante el mes actual
         $data['income_month'] = Inventory::whereBetween('created_at', [
             Carbon::now()->startOfMonth(),
             Carbon::now()->endOfMonth(),
            ])->sum('quantity_in');

        // Productos ingresados durante el mes actual
         $data['product_income'] = Inventory::with('product')
            ->whereBetween('created_at',[
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
         ])
        ->where('quantity_in', '>', 0)
        ->selectRaw('product_id, SUM(quantity_in) as quantity')
        ->groupBy('product_id')
        ->orderByDesc('quantity')
        ->get();

        return view(
            'admin.dashboard.admin',
            compact('data')
        );
    }
}





