<x-admin-layout
 title="Categorías | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard')
],
[
    'name' => 'Categorías',
    'href' => ('admin.categories.index'),
],
[
    'name' => 'Nuevo'
]
]"
>
    <x-wire-card>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">

            @csrf

            <x-wire-input
                label="Nombre"
                name="name"
                placeholder="Nombre de la categoría"
                value="{{ old('name') }}"
            />
            <x-wire-textarea
                label="Descripcíon"
                name="description"
                placeholder="Descripción de la categoría">
                {{ old('description') }}
            </x-wire-textarea>

            <div class="flex justify-end">
                <x-button>
                    Guardar
                </x-button>
            </div>
        </form>

    </x-wire-card>

</x-admin-layout>
