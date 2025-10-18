<?php

namespace Enraiged\Builders\Menu;

class Group
{
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
            $key = [...$keys, $iteration];
            $item['key'] = implode('_', $key);

            $this->items[$index] = key_exists('items', $item)
                ? (new Group($item, $key))->get()
                : (new Item($item))->get();

            $iteration++;
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
