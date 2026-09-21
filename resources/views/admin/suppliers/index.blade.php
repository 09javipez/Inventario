<x-admin-layout
 title="Proveedores | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard')
],
[
    'name' => 'Proveedores',
]
]"
>
    @push('css')
        <style>
            table th span, table td{
                font-size: 0.85rem !important;
            }
        </style>
    @endpush

    @can('update-suppliers')
        <x-slot name="action">
            <x-wire-button href="{{ route('admin.suppliers.create') }}" blue>
                Nuevo
            </x-wire-button>
        </x-slot>
    @endcan

    @livewire('admin.datatables.supplier-table')
</x-admin-layout>
