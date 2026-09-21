<x-admin-layout
 title=" Transferencias | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard')
],
[
    'name' => ' Transferencias',
]
]"
>
    <x-slot name="action">

        @can('create-transfers')
            <x-wire-button href="{{ route('admin.transfers.create') }}" blue>
                Nuevo
            </x-wire-button>
        @endcan

    </x-slot>
    @livewire('admin.datatables.transfer-table')

</x-admin-layout>
