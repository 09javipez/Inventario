<x-admin-layout
 title=" Cotizaciones | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard')
],
[
    'name' => ' Cotizaciones',
]
]"
>
    <x-slot name="action">

        @can('create-quotes')
            <x-wire-button href="{{ route('admin.quotes.create') }}" blue>
                Nueva
            </x-wire-button>
        @endcan

    </x-slot>
    @livewire('admin.datatables.quote-table')

</x-admin-layout>
