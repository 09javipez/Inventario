<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class SupplierTable extends DataTableComponent
{
    //solución para n + 1
    public function builder(): Builder
    {
        return Supplier::query()
            ->with(['identity']);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id','desc');
    }

    //Metodo para exportar
    public function bulkActions(): array
    {
        return [
            'exportSelected' => 'Exportar'
        ];
    }

    public function exportSelected()
    {
        $selected = $this->getSelected();

        $Suppliers = count($selected)
            ? Supplier::whereIn('id', $selected)
                ->with(['identity'])
                ->get()
            : Supplier::with(['identity'])->get();

        return Excel::download(new \App\Exports\SuppliersExport($Suppliers), 'Proveedores.xlsx');
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
                    return view('admin.suppliers.actions',['supplier' =>$row]);
                })
        ];
    }

}
