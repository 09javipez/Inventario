<x-admin-layout
 title="Reportes | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard')
],
[
    'name' => 'Productos con bajo stock',
]
]"
>
    @livewire('Admin.datatables.low-stock-table')
</x-admin-layout>
