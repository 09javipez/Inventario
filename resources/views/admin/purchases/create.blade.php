<x-admin-layout
 title="Compras | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard'),
],
[
    'name' => ' Compras ',
    'href' => route('admin.purchases.index'),
],
[
    'name' => 'Nueva'
]
]"
>
    @livewire('admin.purchase-create')
</x-admin-layout>
