<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ImportOfCategories extends Component
{
    use WithFileUploads;

    public $file;

    public $errors =[];
    public $importedCount = 0;

    // Método para generar el excel.
    public function downloadTemplate()
    {
        return Excel::download(
            new \App\Exports\CategoriesTemplateExport(),
            'categories_template.xlsx'
        );
    }
    // Método para importar excel.
    public function importCategories()
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $categoriesImport = new \App\Imports\CategoriesImport();
        Excel::import($categoriesImport, $this->file);

        $this->errors = $categoriesImport->getErrors();
        $this->importedCount = $categoriesImport->getImportedCount();

        if (count($this->errors) == 0) {
            session()->flash('swal',[
                'icon' => 'success',
                'title' => 'Importacion exitosa',
                'text' => "se han importado {$this->importedCount} categorias.",
            ]);

            return redirect()->route('admin.categories.index');
        }
    }

    public function render()
    {

        return view('livewire.admin.import-of-categories');
    }
}
