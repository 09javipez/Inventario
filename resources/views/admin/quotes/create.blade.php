<x-admin-layout
 title=" Cotizaciones | Vuzalo"
 :breadcrumbs="[
   [
    'name' => 'Dashboard',
    'href' => route('admin.dashboard'),
],
[
    'name' => ' Cotizaciones',
    'href' => route('admin.quotes.index'),
],
[
    'name' => 'Nueva'
]
]"
>
    @livewire('admin.quote-create')
</x-admin-layout>
