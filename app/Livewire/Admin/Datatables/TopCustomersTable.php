<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;


class TopCustomersTable extends DataTableComponent
{

    public function builder(): Builder
    {
        return Sale::query()
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->join('identities','customers.identity_id','=','identities.id')
            ->selectRaw('
                customers.id as id,
                customers.name as name,
                customers.email as email,
                identities.name as identity_type,
                customers.document_number as document_number,
                COUNT(sales.id) as total_sales,
                SUM(sales.total) as total_amount
            ')
            ->groupBy('customers.id',
            'customers.name',
            'customers.email',
            'identities.name',
            'customers.document_number');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id")
                ->label(fn($row) => $row->id)
                ->sortable(),
            Column::make("Cliente")
                ->label(fn($row) => $row->name)
                ->sortable(fn($query, $direction)=> $query->orderBy('name', $direction))
                ->searchable(fn($query, $search) =>$query->orWhere('customers.name', 'like',"%{$search}%")),
            Column::make("Email")
                ->label(fn($row) => $row->email)
                ->sortable(),
            Column::make("Tipo de Identidad")
                ->label(fn($row) => $row->identity_type)
                ->sortable(),
            Column::make("Numero de Documento")
                ->label(fn($row) => $row->document_number)
                ->sortable(),
            Column::make("Ventas Totales")
                ->label(fn($row) => $row->total_sales)
                ->sortable(),
            Column::make("Monto Total")
                ->label(fn($row) => '$/.' . number_format($row->total_amount))
                ->sortable(fn($query, $direction) => $query->orderBy('total_amount', $direction))
        ];
    }
}
