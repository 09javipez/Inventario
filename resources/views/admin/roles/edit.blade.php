<x-admin-layout
 title="Roles | Vuzalo"
 :breadcrumbs="[
   [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard')
    ],
    [
        'name' => 'Dashboard',
        'href' => route('admin.roles.index')
    ],
    [
        'name' => 'Editar',
    ]
]"
>
    <x-wire-card>
        <h1 class="text-2xl font-semibold mb-4">
            Editar rol
        </h1>
        <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="space-y-4">

            @csrf
            @method('PUT')
            <x-wire-input
                label="Nombre del rol"
                name="name"
                placeholder="Escriba un nuevo rol"
                value="{{ old('name', $role->name) }}"
                required
            />
            <div>
                <p class="text-sm font-semibold text-gray-600 mb-2">
                    Permisos
                </p>
                <ul class="columns-1 md:columns-2 lg:columns-4 ">
                    @foreach ($permissions as $permission)
                        <li>
                            <label >
                                <x-checkbox
                                    name="permissions[]"
                                    value="{{ $permission->id }}"
                                    :checked="in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray()))"
                                />
                                <span class="text-sm text-gray-700 dark:text-gray-400">
                                    {{ $permission->name }}
                                </span>
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="flex justify-end">
                <x-wire-button type="submit" blue>
                    Actualizar rol
                </x-wire-button>
            </div>
        </form>
    </x-wire-card>
</x-admin-layout>
