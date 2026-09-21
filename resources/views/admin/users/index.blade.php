<x-admin-layout
 title="Usuarios | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard')
    ],
    [
        'name' => 'Usuarios',
    ]
]"
>
    <x-slot name="action">
        @can('create-users')
            <x-wire-button href="{{ route('admin.users.create') }}" blue>
                <i class="fas fa-plus"></i>
                Nuevo
            </x-wire-button>
        @endcan
    </x-slot>
    @livewire('admin.datatables.user-table')
</x-admin-layout>
