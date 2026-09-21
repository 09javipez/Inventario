@props([
    'breadcrumbs' => [],
    'title' => config('app.name', 'Laravel'),
])

<!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- Fontawesome --}}
        <script src="https://kit.fontawesome.com/c2a11e621c.js" crossorigin="anonymous"></script>

        {{-- SweetAlert2 --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- Wireui --}}
        <wireui:scripts />

        <!-- CSS y JS (Vite) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @stack('css')
</head>
<body class="font-sans antialiased bg-gray-100">

        {{-- NAV --}}
        @include('layouts.includes.admin.navigation')

        {{-- SIDEBAR --}}
        @include('layouts.includes.admin.sidebar')

        <!--  CONTENIDO PRINCIPAL  -->
        <div class="p-4 sm:ml-64 relative">

            <div class="mt-14 flex items-center">

                @include('layouts.includes.admin.breadcrumb')

                @isset($action)
                    <div class="ml-auto">
                        {{ $action }}
                    </div>
                @endisset

            </div>

            {{ $slot }}
        </div>

        @stack('modals')

        @livewireScripts

        {{-- Flowbite --}}
        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

        <script>
            Livewire.on('swal', (data) => {
                Swal.fire(data[0]);
            });
        </script>
        {{-- SweetAlert para formularios --}}
        @if (session('swal'))
            <script>
                Swal.fire(@json(session('swal') ?? []))
            </script>
        @endif
        {{--  --}}
        <script>
            Livewire.on('swal', data => {
                Swal.fire(data[0]);
            });
        </script>

        {{-- Este script  es para comfirmar una eliminación --}}
        <script>
            const forms = document.querySelectorAll('.delete-form');
            forms.forEach(form => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡No podrás revertir esto!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    })
                });
            });
        </script>
        @stack('js')
</body>
</html>
