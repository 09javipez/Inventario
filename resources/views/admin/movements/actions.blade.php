<div class="flex items-center space-x-4">
    @can('read-movements')
        <x-wire-button green wire:click="openModal({{ $movement->id }})">
            <i class="fa-solid fa-envelope text-xl"></i>
        </x-wire-button>
        <x-wire-button blue href="{{ route('admin.movements.pdf', $movement) }}">
            <i class="fa-solid fa-file-pdf text-xl"></i>
        </x-wire-button>
    @endcan
</div>
