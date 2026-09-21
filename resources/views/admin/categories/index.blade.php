<x-admin-layout
 title="Categorías | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard')
],
[
    'name' => 'Categorías',
]
]"
>
    <x-slot name="action">

        <x-wire-button href="{{ route('admin.categories.import') }}" green>
            <i class="fas fa-file-import"></i>
            Importar
        </x-wire-button>

        @can('create-categories')
            <x-wire-button href="{{ route('admin.categories.create') }}" blue>
                <i class="fas fa-plus"></i>
                Nuevo
            </x-wire-button>
        @endcan

    </x-slot>
    @livewire('admin.datatables.category-table')

</x-admin-layout>
