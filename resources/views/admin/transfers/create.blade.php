<x-admin-layout
 title=" Transferencias | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard'),
],
[
    'name' => ' Transferencias',
    'href' => route('admin.transfers.index'),
],
[
    'name' => 'Nuevo'
]
]"
>
    @livewire('admin.transfer-create')
</x-admin-layout>
