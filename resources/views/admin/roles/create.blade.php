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
        'name' => 'Nuevo',
    ]
]"
>
    <x-wire-card>
        <h1 class="text-2xl font-semibold mb-4">
            Nuevo rol
        </h1>
        <form action="{{ route('admin.roles.store') }}" method="POST" class="space-y-4">
            @csrf
            <x-wire-input
                label="Nombre del rol"
                name="name"
                placeholder="Escriba un nuevo rol"
                value="{{ old('name') }}"
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
                                    :checked="in_array($permission->id, old('permissions',[]))"
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
                    Crear rol
                </x-wire-button>
            </div>
        </form>
    </x-wire-card>

</x-admin-layout>
