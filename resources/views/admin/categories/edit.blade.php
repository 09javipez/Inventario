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
    'name' => 'Editar'
]
]"
>

    <x-wire-card>

        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-4">

            @csrf
            @method('PUT')

            <x-wire-input
                label="Nombre"
                name="name"
                placeholder="Nombre de la categoría"
                value="{{ old('name', $category->name) }}"
            />
            <x-wire-textarea
                label="Descripcíon"
                name="description"
                placeholder="Descripción de la categoría">
                {{ old('description',$category->description) }}
            </x-wire-textarea>

            <div class="flex justify-end">
                <x-button>
                    Actualizar
                </x-button>
            </div>
        </form>

    </x-wire-card>

</x-admin-layout>
