<x-admin-layout
 title="Clientes | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard')
],
[
    'name' => 'Clientes',
    'href' => ('admin.customers.index'),
],
[
    'name' => 'Crear cliente'
]
]"
>

    <x-wire-card>

        <form action="{{ route('admin.customers.store') }}" method="POST" class="space-y-4">

            @csrf

            <div class="grid grid-cols-2 gap-4">
                <x-wire-native-select
                    label="Tipo de documento"
                    name="identity_id">
                    @foreach ($identities as $identity)
                        <option value="{{ $identity->id }}" @selected(old('identity_id') == $identity->id)>
                            {{ $identity->name }}
                        </option>
                    @endforeach
                </x-wire-native-select>
                <x-wire-input
                    label="Número de documento"
                    name="document_number"
                    placeholder="Número de documento"
                    value="{{old('identity_number') }}"
                    required
                />
            </div>
            <x-wire-input
                label="Nombre"
                name="name"
                placeholder="Nombre del cliente"
                value="{{ old('name') }}"
            />
            <x-wire-input
                label="Dirección"
                name="address"
                placeholder="Dirección del cliente"
                value="{{ old('address') }}"
            />
            <x-wire-input
                label="Email"
                name="email"
                placeholder="Correo electrónico del cliente"
                value="{{ old('email') }}"
            />
            <x-wire-input
                label="Teléfono"
                name="phone"
                placeholder="Teléfono del cliente"
                value="{{ old('phone') }}"
            />

            <div class="flex justify-end">
                <x-button>
                    Guardar
                </x-button>
            </div>
        </form>

    </x-wire-card>

</x-admin-layout>
