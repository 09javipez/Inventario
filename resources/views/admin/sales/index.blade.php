<x-admin-layout
 title="  Ventas | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard')
],
[
    'name' => 'Ventas',
]
]"
>
    <x-slot name="action">

        @can('create-sales')
            <x-wire-button href="{{ route('admin.sales.create') }}" blue>
                Nuevo
            </x-wire-button>
        @endcan

    </x-slot>
     @livewire('admin.datatables.sale-table')

</x-admin-layout>
