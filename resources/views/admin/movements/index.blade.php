<x-admin-layout
 title=" Entradas y Salidas | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard')
],
[
    'name' => ' Entradas y Salidas',
]
]"
>
    <x-slot name="action">

        @can('create-movements')
            <x-wire-button href="{{ route('admin.movements.create') }}" blue>
                Nueva
            </x-wire-button>
        @endcan

    </x-slot>
    @livewire('admin.datatables.movement-table')

</x-admin-layout>
