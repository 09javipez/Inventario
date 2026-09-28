<x-admin-layout>
    <div class="min-h-screen bg-gray-50">
        <div class="space-y-6">
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 via-indigo-700 to-blue-700 px-6 py-7 shadow-lg">
                <div class="relative z-10 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <div class="mb-2 flex items-center gap-2">
                            <span class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white">
                                Panel administrativo
                            </span>
                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                            <span class="text-xs text-indigo-100">
                                Sistema activo
                            </span>
                        </div>
                        <h1 class="text-3xl font-bold tracking-tight text-white">
                            Bienvenido al Dashboard
                        </h1>
                        <p class="mt-2 max-w-xl text-sm text-indigo-100">
                            Consulta rápidamente el estado general de tu plataforma empresarial.
                        </p>
                    </div>
                    {{-- Fecha --}}
                    <div class="rounded-xl border border-white/20 bg-white/10 px-5 py-4 backdrop-blur-sm">
                        <p class="text-xs font-medium uppercase tracking-wide text-indigo-100">
                            Hoy
                        </p>
                        <p class="mt-1 text-sm font-semibold capitalize text-white">
                            {{ now()->translatedFormat('l, d \d\e F \d\e Y') }}
                        </p>
                    </div>
                </div>
                {{-- Decoración --}}
                <div class="absolute -right-16 -top-20 h-64 w-64 rounded-full bg-white/10"></div>
                <div class="absolute -bottom-24 right-32 h-48 w-48 rounded-full bg-blue-400/10"></div>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                <x-wire-card class="overflow-hidden">
                    <div class="relative">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">
                                    Total de usuarios
                                </p>
                                <p class="mt-3 text-3xl font-bold tracking-tight text-gray-900">
                                    {{ $data['total_users'] ?? 0 }}
                                </p>
                                <p class="mt-2 flex items-center gap-1.5 text-xs text-gray-500">
                                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-100">
                                        <svg
                                            class="h-3 w-3 text-indigo-600"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                            />
                                        </svg>
                                    </span>
                                    Usuarios registrados
                                </p>
                            </div>
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50">
                                <svg
                                    class="h-6 w-6 text-indigo-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                    />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-5 h-1 overflow-hidden rounded-full bg-gray-100">
                            <div class="h-full w-2/3 rounded-full bg-indigo-500"></div>
                        </div>
                    </div>
                </x-wire-card>

                <x-wire-card class="overflow-hidden">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Total de productos
                            </p>
                            <p class="mt-3 text-3xl font-bold tracking-tight text-gray-900">
                                {{ $data['total_products'] ?? 0 }}
                            </p>
                            <p class="mt-2 flex items-center gap-1.5 text-xs text-gray-500">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-100">
                                    <svg
                                        class="h-3 w-3 text-blue-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                        />
                                    </svg>
                                </span>
                                Productos registrados
                            </p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50">
                            <svg
                                class="h-6 w-6 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-5 h-1 overflow-hidden rounded-full bg-gray-100">
                        <div class="h-full w-2/3 rounded-full bg-blue-500"></div>
                    </div>
                </x-wire-card>

                <x-wire-card class="overflow-hidden">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Ingresos de productos
                            </p>
                            <p class="mt-3 text-3xl font-bold tracking-tight text-gray-900">
                                {{ $data['income_month'] ?? 0 }}
                            </p>
                            <p class="mt-2 flex items-center gap-1.5 text-xs text-gray-500">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-100">
                                    <svg
                                        class="h-3 w-3 text-emerald-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 4v16m8-8H4"
                                        />
                                    </svg>
                                </span>
                                Unidades ingresadas este mes
                            </p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50">
                            <svg
                                class="h-6 w-6 text-emerald-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4v16m8-8H4"
                                />
                            </svg>
                        </div>
                    </div>

                    <div class="mt-5 h-1 overflow-hidden rounded-full bg-gray-100">
                        <div class="h-full w-2/3 rounded-full bg-emerald-500"></div>
                    </div>
                </x-wire-card>
            </div>

            <x-wire-card>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-50">
                                <svg
                                    class="h-5 w-5 text-indigo-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                    />
                                </svg>
                            </span>
                            <h2 class="text-lg font-bold text-gray-900">
                                Usuarios recientes
                            </h2>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            Últimos usuarios registrados en el sistema
                        </p>
                    </div>
                </div>

                <div class="mt-6 overflow-hidden rounded-xl border border-gray-100">
                    <ul class="divide-y divide-gray-100">
                        @forelse ($data['recent_users'] ?? [] as $user)
                            <li class="flex items-center justify-between gap-4 p-4 transition hover:bg-gray-50">
                                {{-- Usuario --}}
                                <div class="flex min-w-0 items-center gap-4">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-100 to-blue-100">
                                        <span class="text-sm font-bold text-indigo-600">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-gray-900">
                                            {{ $user->name }}
                                        </p>
                                        <p class="mt-1 truncate text-xs text-gray-500">
                                            {{ $user->email }}
                                        </p>
                                    </div>
                                </div>
                                {{-- Fecha --}}
                                <div class="shrink-0 text-right">
                                    <p class="text-xs font-medium text-gray-400">
                                        Registrado
                                    </p>
                                    <p class="mt-1 text-xs font-medium text-gray-600">
                                        {{ $user->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </li>
                        @empty

                            <li class="py-12 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">
                                    <svg
                                        class="h-6 w-6 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                        />
                                    </svg>
                                </div>
                                <p class="mt-3 text-sm font-medium text-gray-500">
                                    No hay usuarios registrados.
                                </p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </x-wire-card>
        </div>
    </div>
</x-admin-layout>




