<?php

namespace App\View\Composers;

use App\Services\Sidebar\ItemGroup;
use App\Services\Sidebar\ItemHeader;
use App\Services\Sidebar\ItemLink;

class SidebarComposer
{
    public function compose($view)
    {
        $items = collect(config('sidebar'))
            ->map(function ($item) {
                return $this->parseItem($item);
            });

        $view->with('itemsSidebar', $items);
    }

    public function parseItem(array $item)
    {
        switch ($item['type']) {
            case 'header':
                return new ItemHeader(
                    title: $item['title'],
                    can: $item['can'] ?? []
                );

                break;
            case 'link':
                return new ItemLink(
                    title: $item['title'],
                    url: isset($item['route']) ? route($item['route']) : '#',
                    icon: $item['icon'] ?? 'fa-regular fa-circle',
                    active: isset($item['active']) ? request()->routeIs($item['active']) : false,
                    can: $item['can'] ?? []
                );
                break;
            case 'group':
                $group = new ItemGroup(
                    title: $item['title'],
                    icon: $item['icon'] ?? 'fa-regular fa-circle',
                    active: isset($item['active']) ? request()->routeIs($item['active']) : false
                );
                foreach ($item['items'] as $subItem) {
                    $group->add($this->parseItem($subItem));
                }
                return $group;
                break;

            default:
                throw new \InvalidArgumentException("Unknown item type: {$item['type']} ");
                break;
        }
    }
}
