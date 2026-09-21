<x-admin-layout
 title="Usuarios | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard')
    ],
    [
    'name' => 'Usuarios',
    'href' => route('admin.users.index')
    ],
    [
        'name' => 'Editar',
    ]
]"
>
    <x-wire-card>
        <h1 class="text-2xl font-semibold mb-4">Editar Usuario</h1>
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <x-wire-input
                    label="Nombre"
                    name="name"
                    placeholder="Nombre del usuario"
                    value="{{ old('name',$user->name) }}"
                    required

                />
                <x-wire-input
                    label="Correo Electronico"
                    name="email"
                    type="email"
                    required
                    placeholder="Correo electronico del usuario"
                    value="{{ old('email',$user->email) }}"
                />
                <x-wire-input
                    label="Contraseña"
                    name="password"
                    type="password"

                    placeholder="Contraseña del usuario"
                />
                <x-wire-input
                    label="Confirmar Contraseña"
                    name="password_confirmation"
                    type="password"

                    placeholder="Confirma la Contraseña del usuario"
                />
                <x-wire-native-select label="Rol" name="role_id" >
                    <option value="">Seleccione un rol</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}"
                            @selected(old('role_id', optional($user->roles->first())->id) == $role->id)>
                            {{ $role->name }}
                        </option>
                    @endforeach
                    </x-wire-native-select>
            </div>

            <div class="flex justify-end mt-4">
                <x-wire-button
                    type="submit"
                    blue
                >
                    Actualizar Usuario
                </x-wire-button>
            </div>
        </form>
    </x-wire-card>

</x-admin-layout>
