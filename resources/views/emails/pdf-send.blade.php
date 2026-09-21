@component('mail::message')
    #{{ $form['document'] }}

    Cliente: {{ $form['client'] }}

    Adjunto encontrará el documento en formato PDF.

    Gracias, <br>
    {{ config('app.name') }}
@endcomponent
