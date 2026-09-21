<x-admin-layout
 title=" Ordenes de Compra | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard'),
],
[
    'name' => ' Ordenes de Compra',
    'href' => route('admin.purchase-orders.index'),
],
[
    'name' => 'Nuevo'
]
]"
>
    @livewire('admin.purchase-orders-create')
</x-admin-layout>
