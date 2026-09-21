<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PurchaseOrdersExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    protected $purchaseOrders;

    public function __construct($purchaseOrders)
    {
       $this->purchaseOrders = $purchaseOrders;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->purchaseOrders->map(function ($purchaseOrder){
            return[
                $purchaseOrder->id,
                $purchaseOrder->date,
                $purchaseOrder->serie,
                $purchaseOrder->correlative,
                $purchaseOrder->supplier->identity->name,
                $purchaseOrder->supplier->document_number,
                $purchaseOrder->supplier->name,
                $purchaseOrder->total,



            ];
        });
    }
    public function headings(): array
    {
        return[
            'iD',
            'Fecha',
            'Serie',
            'Correlativo',
            'Proveedor (Identidad)',
            'N° Documento',
            'Nombre del Proveedor',
            'Total'


        ];
    }
    //Estilos para el archivo de excel
    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        $fullRange = 'A1:' . $lastColumn . $lastRow;

        return[
            1 => [
                 'font' => [
                    'bold' => true,
                 ],
                 'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFDDEBF7']
                 ],
                 'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                 ]
            ],
            //nivel de celdas
            $fullRange => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000']
                    ],
                ],
            ]
        ];
    }
    //Podemos seleccionar una cassilla con la que se descarga excel
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){
                $event->sheet->getDelegate()->setSelectedCell('A1');
            }
        ];
    }
}
