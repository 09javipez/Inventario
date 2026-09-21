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
    @livewire('admin.datatables.top-customers-table')
</x-admin-layout>
