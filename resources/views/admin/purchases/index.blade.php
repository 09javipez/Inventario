<x-admin-layout
 title="  Compras | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard')
],
[
    'name' => 'Compras',
]
]"
>
    <x-slot name="action">

        @can('create-purchases')
            <x-wire-button href="{{ route('admin.purchases.create') }}" blue>
                Nuevo
            </x-wire-button>
        @endcan

    </x-slot>
     @livewire('admin.datatables.purchase-table')

</x-admin-layout>
