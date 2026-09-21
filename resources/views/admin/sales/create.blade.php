<x-admin-layout
 title="Ventas | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard'),
],
[
    'name' => ' Ventas ',
    'href' => route('admin.sales.index'),
],
[
    'name' => 'Nueva'
]
]"
>
    @livewire('admin.sale-create')
</x-admin-layout>
