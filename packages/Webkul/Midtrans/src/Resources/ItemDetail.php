<?php

namespace Webkul\Midtrans\Resources;

class ItemDetail extends AbstractResource
{
    protected $items = [];

    public function addItem($id, $name, $price, $qty)
    {
        $this->items[] = [
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'quantity' => $qty
        ];
    }

    public function getArray()
    {
        $this->validateData();

        return $this->items;
    }
}
