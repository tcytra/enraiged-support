<?php

namespace Enraiged\Builders\Menu;

use Enraiged\Builders\Secure\AssertSecure;
use Enraiged\Builders\Secure\RoleAssertions;

class Group
{
    use AssertSecure, RoleAssertions;

    /** @var  array  The menu items in this group. */
    protected array $items = [];

    /** @var  string  The menu item key. */
    protected $key;

    /** @var  string  The menu item text label. */
    protected $label;

    /**
     *  @param  array   $group
     *  @param  array   $keys
     */
    public function __construct(array $group, array $keys)
    {
        $iteration = 0;

        if (count($keys)) {
            $this->key = implode('_', $keys);
            $this->label = $group['label'];
        }

        foreach ($group['items'] as $index => $item) {
            if ($this->assertSecure($item)) {
                $key = [...$keys, $iteration];
                $item['key'] = implode('_', $key);

                if (key_exists('items', $item)) {
                    $new = (new Group($item, $key))->get();

                    if (count($new['items'])) {
                        $this->items[$index] = $new;

                        $iteration++;
                    }

                } else {
                    $this->items[$index] = (new Item($item))->get();

                    $iteration++;
                }

                $iteration++;
            }
        }
    }

    /**
     *  Return the assembled menu group.
     *
     *  @return array
     */
    public function get(): array
    {
        return [
            'items' => $this->items,
            'key' => $this->key,
            'label' => $this->label,
        ];
    }

    /**
     *  Return only the menu group items.
     *
     *  @return array
     */
    public function items(): array
    {
        return $this->items;
    }
}
