<x-admin-layout
 title=" Ordenes de Compra | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard')
],
[
    'name' => ' Ordenes de Compra',
]
]"
>
    <x-slot name="action">
        @can('create-purchase-orders')
            <x-wire-button href="{{ route('admin.purchase-orders.create') }}" blue>
                Nuevo
            </x-wire-button>
        @endcan
    </x-slot>
    @livewire('admin.datatables.purchase-order-table')

</x-admin-layout>
