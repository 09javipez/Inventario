<?php

namespace App\Livewire\Admin\Datatables;

use App\Models\Transfer;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateRangeFilter;

class TransferTable extends DataTableComponent
{
    //protected $model = Movement::class;

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
            Column::make("Almacen Origen", "originWarehouse.name")
                ->sortable(),
            Column::make("Almacen Destino", "destinationWarehouse.name")
                ->sortable(),
            Column::make("Total", "total")
                ->sortable()
                ->format(fn($value) => '$' . number_format($value, 2, '.',',')),
            Column::make('Acciones')
                ->label(function($row){
                    return view('admin.transfers.actions', ['transfer' => $row]);
                })

        ];
    }
    //solución para n + 1
    public function builder(): Builder
    {
        return Transfer::query()
            ->with(['originWarehouse','destinationWarehouse',]);
    }
    //Propiedades para enviar correos
    public $form = [
       'open' => false,
       'document' => '',
       'client' => '',
       'email' => '',
       'model' => null,
       'view_pdf_patch' => 'admin.transfers.pdf',
    ];
    //Metodo
    public function openModal(Transfer $transfer)
    {
        $this->form['open'] = true;
        $this->form['document'] = 'Transferencia' . $transfer->serie . '-' . $transfer->correlative;
        $this->form['client'] = $transfer->originWarehouse->name;
        $this->form['email'] = '';
        $this->form['model'] = $transfer;

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
