<?php

namespace App\Livewire\Admin\Datatables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LowStockTable extends DataTableComponent
{
    public function builder(): Builder
    {
        /*
        |--------------------------------------------------------------------------
        | Traemos los productos junto con sus inventarios
        |--------------------------------------------------------------------------
        */
        return Product::query()
            ->with('inventories');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),

            Column::make('Producto', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Stock actual')
                ->label(function ($row) {
                    return $row->stock;
                }),

            Column::make('Stock mínimo', 'min_stock')
                ->sortable(),

            Column::make('Faltante')
                ->label(function ($row) {
                    $faltante = $row->min_stock - $row->stock;

                    return max(0, $faltante);
                }),
        ];
    }
}




