<x-admin-layout
 title="Productos | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard')
],
[
    'name' => 'Productos',
    'href' => ('admin.products.index'),
],
[
    'name' => 'Nuevo'
]
]"
>
    <x-wire-card>

        <form action="{{ route('admin.products.store') }}" method="POST" class="space-y-4">

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
            <x-wire-input
                type="number"
                label="Precio"
                name="price"
                placeholder="Precio del producto"
                value="{{ old('price') }}"
            />

            <x-wire-native-select
                label="Categoría"
                name="category_id"
            >
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </x-wire-native-select>
            {{-- Minimo stock --}}
            <x-wire-input
                label="Stock minimo"
                name="min_stock"
                type="number"
                placeholder="Cantidad minima de stock"
                value="{{ old('min_stock', 5) }}"
            />

            <div class="flex justify-end">
                <x-button>
                    Guardar
                </x-button>
            </div>
        </form>

    </x-wire-card>

</x-admin-layout>
