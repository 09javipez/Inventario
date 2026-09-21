<div>
    <x-wire-card>
        <h1 class="text-2xl font-semibold text-gray-800 mb-6">
            Importar productos desde Excel
        </h1>
        <x-wire-button blue wire:click="downloadTemplate">
            <i class="fas fa-file-excel"></i>
            Descargar plantilla
        </x-wire-button>
        <p class="text-sm text-gray-500 mt-1">
            Completa la platilla con los datos de tus productos y subelos aqui.
        </p>

        <div class="mt-4">
            <input type="file" accept=".xlsx, .xls" wire:model="file" />

            <x-input-error for="file" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-wire-button
                green
                wire:click="importProducts"
                wire:loading.attr="disabled"
                wire:target="importProducts"
                spinner="importProducts"
                >
                <i class="fas fa-upload mr-2"></i>
                Importar productos
            </x-wire-button>
        </div>

        @if(count($errors))
            <div class="mt-4">
                <div class="p-4 bg-yellow-100 border border-yellow-100 rounded-md text-black mb-3">
                    @if ($importedCount)
                        <i class="fas fa-triangle-exclamation mr-2"></i>
                        <strong>Importacion completada parcialmente</strong>

                        <p class="mt-1 text-sm">
                            Algunos productos no se pudieron cargar debido a errores.
                        </p>
                    @else
                        <i class="fas fa-xmark mr-2"></i>
                        <strong>No se importo ningun producto</strong>
                        <p class="mt-1 text-sm">
                            Todos los productos tienen errores o el archivo no es valido.
                        </p>
                    @endif
                </div>
                <ul class="space-y-2">
                    @foreach ($errors as $error )
                        <li class="p-3 bg-red-50 border border-red-200 rounded">
                            <p class="text-red-700 font-semibold">
                                <i class="fas fa-file-pen"></i>
                                Fila{{ $error ['row'] }}:
                            </p>

                            <ul class="list-disc list-inside mt-1">
                                @foreach ($error['errors'] as $message )
                                    <li class="text-red-600 text-sm">
                                        {{ $message }}
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </x-wire-card>
</div>
