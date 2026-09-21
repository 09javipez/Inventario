<x-admin-layout
 title="Categorias | Vuzalo"
 :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard')
    ],
    [
        'name' => 'Productos',
        'href' => route('admin.categories.index')
    ],
    [
        'name' => 'Importar',
    ]
]"
>
    @livewire('admin.import-of-categories')
</x-admin-layout>
