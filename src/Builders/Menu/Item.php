<?php

namespace Enraiged\Builders\Menu;

class Item
{
    /** @var  string  The menu item icon. **/
    protected $icon;

    /** @var  string  The menu item key. */
    protected $key;

    /** @var  string  The menu item text label. */
    protected $label;

    /** @var  array|string  The menu item routing directives. */
    protected $route;

    /**
     *  @param  array   $item
     */
    public function __construct(array $item)
    {
        foreach ($item as $index => $value) {
            if (property_exists($this, $index)) {
                $this->{$index} = $value;
            }
        }
    }

    /**
     *  Return the assembled menu items.
     *
     *  @return array
     */
    public function get(): array
    {
        return [
            'icon' => $this->icon,
            'key' => $this->key,
            'label' => $this->label,
            'route' => $this->route,
        ];
    }
}
