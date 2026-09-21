<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class ProductsImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    private array $errors = [];

    private int $importedCount = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            $data = $row->toArray();

            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'sku' => 'required|string|max:100',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required',
            ]);

            if ($validator->fails()) {

                $this->errors[] = [
                    'row' => $index + 2,
                    'errors' => $validator->errors()->all(),
                ];

                continue;
            }

            // Verificar SKU duplicado
            if (Product::where('sku', $data['sku'])->exists()) {

                $this->errors[] = [
                    'row' => $index + 2,
                    'errors' => [
                        "El SKU '{$data['sku']}' ya existe."
                    ],
                ];

                continue;
            }

            // Verificar categoría existente
            if (! Category::where('id', $data['category_id'])->exists()) {

                $this->errors[] = [
                    'row' => $index + 2,
                    'errors' => [
                        "La categoría ID {$data['category_id']} no existe."
                    ],
                ];

                continue;
            }

            Product::create($data);
            $this->importedCount++;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }
}
