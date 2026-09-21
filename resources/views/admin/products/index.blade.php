<x-admin-layout
 title="Productos | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard')
],
[
    'name' => 'Productos',
]
]"
>
    @push('css')
        <style>
            table th span, table td{
                font-size: 0.85rem !important;
            }
            .image-product{
                width: 8rem;
                height: 4.5rem;
                object-fit: cover;
                object-position: center;
            }
        </style>
    @endpush

    @can('create-products')
        <x-slot name="action">
            <x-wire-button href="{{ route('admin.products.import') }}" green>
                <i class="fas fa-file-import"></i>
                Importar
            </x-wire-button>

            <x-wire-button href="{{ route('admin.products.create') }}" blue>
                <i class="fas fa-plus"></i>
                Nuevo
            </x-wire-button>
        </x-slot>
    @endcan
    @livewire('admin.datatables.product-table')

</x-admin-layout>
