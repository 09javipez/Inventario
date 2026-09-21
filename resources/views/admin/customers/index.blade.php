<x-admin-layout
 title="Clientes | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard')
],
[
    'name' => 'Clientes',
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

    <x-slot name="action">

        @can('create-customers')
            <x-wire-button href="{{ route('admin.customers.create') }}" blue>
                Nuevo
            </x-wire-button>
        @endcan

    </x-slot>

    @livewire('admin.datatables.customer-table')
</x-admin-layout>
