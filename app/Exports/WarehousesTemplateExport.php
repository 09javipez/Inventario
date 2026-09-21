<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WarehousesTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{

    public function array(): array
    {
        return [];
    }

    public function headings(): array
    {
        return[
            'name',
            'location',

        ];
    }
    //Estilos para el archivo de excel
    public function styles(Worksheet $sheet)
    {
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
            'A1:B2' => [
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
