<div class="flex items-center space-x-4">
    @can('read-purchase-orders')
        <x-wire-button green wire:click="openModal({{ $purchaseOrder->id }})">
            <i class="fa-solid fa-envelope text-xl"></i>
        </x-wire-button>

        <x-wire-button blue href="{{ route('admin.purchase-orders.pdf', $purchaseOrder) }}">
            <i class="fa-solid fa-file-pdf text-xl"></i>
        </x-wire-button>
    @endcan
</div>
