<x-admin-layout>
    @role(['admin', 'Administador','editor','viewe'])
        @include('admin.dashboard.admin')
    @endrole
</x-admin-layout>
