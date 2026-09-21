<x-admin-layout
 title="Roles | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard')
    ],
    [
        'name' => 'Roles',
    ]
]"
>
    @can('create-roles')
        <x-slot name="action">
            <x-wire-button href="{{ route('admin.roles.create') }}" blue>
                <i class="fas fa-plus"></i>
                Nuevo
            </x-wire-button>
        </x-slot>
    @endcan
    @livewire('admin.datatables.role-table')
</x-admin-layout>
