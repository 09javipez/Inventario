<?php

namespace App\Services\Sidebar;

use Illuminate\Support\Facades\Gate;

class ItemHeader implements ItemInterface
{
    private string $title;
    private array $can;

    public function __construct(string $title, array $can = [])
    {
        $this->title = $title;
        $this->can = $can;
    }

    public function render(): string
    {
        return <<<HTML
            <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">
                {$this->title}
            </div>
        HTML;
    }
    public function authorize(): bool
    {
        return count($this->can)
           ? Gate::any($this->can)
           : true;
    }
}
