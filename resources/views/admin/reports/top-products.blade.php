<x-admin-layout
 title="Reportes | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard')
],
[
    'name' => 'Productos mas vendidos',
]
]"
>
    @livewire('admin.datatables.top-products-table')
</x-admin-layout>
