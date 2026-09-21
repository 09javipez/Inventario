<?php

namespace App\Livewire\Admin\Datatables;

use App\Models\Purchase;
use App\Models\Supplier;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateRangeFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;

class PurchaseTable extends DataTableComponent
{
    //solución para n + 1
    public function builder(): Builder
    {
        return Purchase::query()
            ->with(['supplier']);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('id','desc');

        //Metodo para enviar correos
        $this->setConfigurableAreas([
            'after-wrapper' => [
                'admin.pdf.modal',
            ],
        ]);
    }
    //Metodo para flitar por fecha
    public function filters(): array
    {
        return[
            DateRangeFilter::make('Fecha')
                ->config([
                    'placeholder' => 'Selecciona el rango de fecha',
                ])
                ->filter(function($query, $dateRange){
                   $query->whereBetween('date',[
                    $dateRange['minDate'],
                    $dateRange['maxDate'],
                   ]);
                }),
                //Filtro por proveedores con metodo livewire
            MultiSelectFilter::make('Proveedor')
                ->options(
                    Supplier::query()
                        ->orderBy('name')
                        ->get()
                        ->keyBy('id')
                        ->map(fn($tag) => $tag->name)
                        ->toArray()
                )
                ->filter(function($query, array $selected){
                    $query->whereIn('supplier_id', $selected);
                }),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Fecha", "date")
                ->sortable()
                ->format(fn($value)=> $value->format('Y-m-d')),
            Column::make("Serie", "serie")
                ->sortable(),
            Column::make("Correlativo", "correlative")
                ->sortable(),
            Column::make("Documento", "supplier.document_number")
                ->sortable(),
            Column::make("Razón social", "supplier.name")
                ->sortable(),
            Column::make("Total", "total")
                ->sortable()
                ->format(fn($value) => '$' . number_format($value, 2, '.',',')),
            Column::make('Acciones')
                ->label(function($row){
                    return view('admin.purchases.actions', ['purchase' => $row]);
                })
        ];
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

        $purchases = count($selected)
            ? Purchase::whereIn('id', $selected)
                ->with(['supplier.identity'])
                ->get()
            : Purchase::with(['supplier.identity'])->get();

        return Excel::download(new \App\Exports\PurchasesExport($purchases), 'Compras.xlsx');
    }
    //Propiedades para enviar correos
    public $form = [
       'open' => false,
       'document' => '',
       'client' => '',
       'email' => '',
       'model' => null,
       'view_pdf_patch' => 'admin.purchases.pdf',
    ];
    //Metodo
    public function openModal(Purchase $purchase)
    {
        $this->form['open'] = true;
        $this->form['document'] = 'Compra' . $purchase->serie . '-' . $purchase->correlative;
        $this->form['client'] = $purchase->supplier->document_number . '-' . $purchase->supplier->name;
        $this->form['email'] = $purchase->supplier->email;
        $this->form['model'] = $purchase;

    }
    public function sendEmail()
    {
        $this->validate([
            'form.email' => 'required|email',
        ]);
        //Llamar a un mailable
        Mail::to($this->form['email'])
            ->send(new \App\Mail\PdfSend($this->form));

        $this->dispatch('swal',[
            'icon' => 'success',
            'title' => 'Correo enviado',
            'text' => 'El correo ha sido enviado correctamente.',
        ]);

        $this->reset('form');
    }
}
