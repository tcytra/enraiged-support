<?php

namespace Enraiged\Builders;

class Menu
{
    use Secure\AssertSecure;

    /** @var  array  The menu items. */
    protected array $items = [];

    /** @var  bool  Whether this menu displays static (all groups open). */
    protected bool $static = false;

    /**
     *  @param  array   $menu
     */
    public function __construct(array $menu)
    {
        if (key_exists('items', $menu)) {
            if ($this->assertSecure($menu)) {
                $this->items = (new Menu\Group($menu, []))->items();

                unset($menu['items']);
            }
        }

        foreach ($menu as $index => $value) {
            if (property_exists($this, $index)) {
                $this->{$index} = $value;
            }
        }
    }

    /**
     *  Return the assembled menu.
     *
     *  @return array
     */
    public function get()
    {
        return [
            'items' => $this->items,
            'static' => $this->static,
        ];
    }

    /**
     *  Return only the menu items.
     *
     *  @return array
     */
    public function items(): array
    {
        return $this->items;
    }
}
