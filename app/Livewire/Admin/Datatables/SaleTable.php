<?php

namespace App\Livewire\Admin\Datatables;

use App\Models\Purchase;
use App\Models\Sale;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateRangeFilter;

class SaleTable extends DataTableComponent
{
    //protected $model = PurchaseOrder::class;

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
                })
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
            Column::make("Documento", "customer.document_number")
                ->sortable(),
            Column::make("Razón social", "customer.name")
                ->sortable(),
            Column::make("Total", "total")
                ->sortable()
                ->format(fn($value) => '$' . number_format($value, 2, '.',',')),
            Column::make('Acciones')
                ->label(function($row){
                    return view('admin.sales.actions', ['sale' => $row]);
                })
        ];
    }
    //solución para n + 1
    public function builder(): Builder
    {
        return Sale::query()
            ->with(['customer']);
    }
    //Propiedades para enviar correos
    public $form = [
       'open' => false,
       'document' => '',
       'client' => '',
       'email' => '',
       'model' => null,
       'view_pdf_patch' => 'admin.sales.pdf',
    ];
    //Metodo
    public function openModal(Sale $sale)
    {
        $this->form['open'] = true;
        $this->form['document'] = 'Venta' . $sale->serie . '-' . $sale->correlative;
        $this->form['client'] = $sale->customer->document_number . '-' . $sale->customer->name;
        $this->form['email'] = $sale->customer->email;
        $this->form['model'] = $sale;

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
