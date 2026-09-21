<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;

class CustomerTable extends DataTableComponent
{
    //protected $model = Customer::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id','desc');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Tipo documento", "identity.name")
                ->sortable(),
            Column::make("N° Documento", "document_number")
                ->searchable()
                ->sortable(),
            Column::make("Razón social", "name")
                ->searchable()
                ->sortable(),
            Column::make("Correo", "email")
                ->sortable(),
            Column::make("Teléfono", "phone")
                ->sortable(),
            Column::make('Acciones')
                ->label(function($row){
                    return view('admin.customers.actions',['customer' =>$row]);
                })
        ];
    }
    //solución para n + 1
    public function builder(): Builder
    {
        return Customer::query()
            ->with(['identity']);
    }
}
