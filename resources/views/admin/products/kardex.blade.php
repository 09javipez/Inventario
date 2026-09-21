<x-admin-layout
title="Productos | Vuzalo"
:breadcrumbs="[
    [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard')
    ],
    [
        'name' => 'Productos',
        'href' => ('admin.products.index'),
    ],
    [
        'name' => 'Kardex'
    ]
]"
>

    @livewire('admin.kardex', ['product' => $product])

</x-admin-layout>
