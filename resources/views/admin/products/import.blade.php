<x-admin-layout
 title="Productos | Vuzalo"
 :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard')
    ],
    [
        'name' => 'Productos',
        'href' => route('admin.products.index')
    ],
    [
        'name' => 'Importacion',
    ]
]"
>
    @livewire('admin.import-of-products')
</x-admin-layout>
