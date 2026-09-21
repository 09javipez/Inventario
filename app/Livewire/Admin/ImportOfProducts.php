<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;

class ImportOfProducts extends Component
{
    use WithFileUploads;

    public $file;

    public $errors =[];
    public $importedCount = 0;

    // Método para generar el excel.
    public function downloadTemplate()
    {
        return Excel::download(
            new \App\Exports\ProductsTemplateExport(),
            'products_template.xlsx'
        );
    }

    // Método para importar excel.
    public function importProducts()
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $productsImport = new \App\Imports\ProductsImport();
        Excel::import($productsImport, $this->file);

        $this->errors = $productsImport->getErrors();
        $this->importedCount = $productsImport->getImportedCount();

        if (count($this->errors) == 0) {
            session()->flash('swal',[
                'icon' => 'success',
                'title' => 'Importacion exitosa',
                'text' => "se han importado { $this->importedCount} productos.",
            ]);

            return redirect()->route('admin.products.index');
        }
    }

    public function render()
    {
        return view('livewire.admin.import-of-products');
    }
}
