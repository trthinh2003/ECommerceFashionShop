<?php

namespace App\Models;

class Cart
{
    public $items = [];
    public $totalQty = 0;
    public $totalPrice = 0;
    public $cartQuantity = 0;

    public function __construct()
    {
        $this->items = session('cart') ? session('cart') : [];
        $this->totalQty = $this->getTotalQuantity();
        $this->totalPrice = $this->getTotalPrice();
        $this->cartQuantity = 1;
    }

    public function add($product, $quantity = 1, $productVariant = null)
    {
        if (!empty($this->items[$product->id])) {
            $this->items[$product->id]->quantity += $quantity;
        } else {
            $items = [
                'id' => $product->id,
                'name' => $product->product_name,
                'image' => $product->image,
                'price' => $product->price,
                'quantity' => $quantity,
                'color' => $productVariant->color,
                'size' => $productVariant->size
            ];
            $this->items[$product->id] = (object)$items;
        }

        session(['cart' => $this->items]);
    }




    public function remove($id)
    {
        if (!empty($this->items[$id])) {
            unset($this->items[$id]);
            session(['cart' => $this->items]);
        }
    }

    private function getTotalQuantity()
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->quantity;
        }
        return $total;
    }

    private function getTotalPrice()
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->quantity * $item->price;
        }
        return $total;
    }
}
