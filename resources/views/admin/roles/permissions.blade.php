<div class="flex flex-wrap gap-1">
    @forelse ($permissions as $permission)
        <x-wire-badge blue>
            {{ $permission->name }}
        </x-wire-badge>
    @empty
        <x-wire-badge secundary>
            Sin permisos
        </x-wire-badge>
    @endforelse
</div>
