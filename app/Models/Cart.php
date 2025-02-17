<?php

namespace App\Models;
class Cart {

    public $items = [];
    public $totalQty = 0;
    public $totalPrice = 0;

    public function __construct(){
        $this->items = session('cart') ? session('cart') : [];
        $this->totalQty = $this->getTotalQuantity();
        $this->totalPrice = $this->getTotalPrice();
    }

    public function add($product){
        $quantity = request('quantity', 1);
        if(!empty($this->items[$product->id])){
            $this->items[$product->id]['quantity'] += $quantity;
        } else {
            $discount = 15;
            $sale_price = $product->price * $discount/ 100;
            $items = [
                'id' => $product->id,
                'name' => $product->product_name,
                'image' => $product->image,
                'price' => $sale_price > 0 ? $sale_price : $product->price,
                'quantity' => $quantity
            ];
            $this->items[$product->id] = (object)$items;
        }

        session(['cart' => $this->items]);
        // dd($product, $quantity);
    }

    private function getTotalQuantity(){
        $total = 0;
        foreach($this->items as $item){
            $total += $item->quantity;
        }
        return $total;
    }


    private function getTotalPrice(){
        $total = 0;
        foreach($this->items as $item){
            $total += $item->quantity * $item->price;
        }
        return $total;
    }
}

