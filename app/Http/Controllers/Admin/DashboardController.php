<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [];

        $inicioMes = Carbon::now()->startOfMonth();
        $finMes = Carbon::now()->endOfMonth();

            $data['sales_month'] = Sale::whereBetween('date', [
                $inicioMes,
                $finMes
            ])->sum('total');

            $data['purchases_month'] = Purchase::whereBetween('date', [
                $inicioMes,
                $finMes
            ])->sum('total');

            $data['total_products'] = Product::count();

            $lastInventories = Inventory::query()
                ->select('product_id', 'warehouse_id')
                ->selectRaw('MAX(id) as last_inventory_id')
                ->groupBy('product_id', 'warehouse_id')
                ->get();

            $data['total_stock'] = Inventory::whereIn(
                'id',
                $lastInventories->pluck('last_inventory_id')
            )->sum('quantity_balance');


            $data['monthly'] = [];

            for ($i = 5; $i >= 0; $i--) {

                $date = Carbon::now()->subMonths($i);

                $data['monthly'][] = [
                    'month' => $date->translatedFormat('M'),

                    'sales' => Sale::whereYear('date', $date->year)
                        ->whereMonth('date', $date->month)
                        ->sum('total'),

                    'purchases' => Purchase::whereYear('date', $date->year)
                        ->whereMonth('date', $date->month)
                        ->sum('total'),
                ];
            }


            return view(
                'admin.dashboard.admin',
                compact('data')
            );
    }
}
