<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ImportOfWarehouses extends Component
{
    use WithFileUploads;

    public $file;

    public $errors =[];
    public $importedCount = 0;

    // Método para generar el excel.
    public function downloadTemplate()
    {
        return Excel::download(
            new \App\Exports\WarehousesTemplateExport(),
            'warehouses_template.xlsx'
        );
    }
    // Método para importar excel.
    public function importWarehouses()
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $warehousesImport = new \App\Imports\WarehousesImport();
        Excel::import($warehousesImport, $this->file);

        $this->errors = $warehousesImport->getErrors();
        $this->importedCount = $warehousesImport->getImportedCount();

        if (count($this->errors) == 0) {
            session()->flash('swal',[
                'icon' => 'success',
                'title' => 'Importacion exitosa',
                'text' => "se han importado {$this->importedCount} almacenes.",
            ]);

            return redirect()->route('admin.warehouses.index');
        }
    }

    public function render()
    {

        return view('livewire.admin.import-of-warehouses');
    }
}
