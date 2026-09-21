<?php

namespace App\Services\Sidebar;

use Illuminate\Support\Facades\Gate;

class ItemLink implements ItemInterface
{
    private string $title;
    private string $url;
    private string $icon;
    private bool $active;
    private array $can;

    public function __construct(string $title, string $url, string $icon, bool $active, array $can = [])
    {
        $this->title = $title;
        $this->url = $url;
        $this->icon = $icon;
        $this->active = $active;
        $this->can = $can;
    }

    public function render(): string
    {
        $activeClass = $this->active ? 'bg-gray-100' : '';

        return <<<HTML
            <a href="{$this->url}"
                class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {$activeClass}">
                    <span class="w-6 h-6 inline-flex justify-center items-center">
                        <i class="{$this->icon}"></i>
                    </span>
                <span class="ms-3">{$this->title}</span>
            </a>
        HTML;
    }
    public function authorize(): bool
    {
        return count($this->can)
            ? Gate::any($this->can)
            : true;
    }
}
