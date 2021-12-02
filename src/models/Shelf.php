<?php

namespace App\Models;

class Shelf
{
    private $maxProductCount;
    private $products;

    public function __construct($maxProductCount)
    {
        $this->maxProductCount = $maxProductCount;
        $this->products = [];
    }

    public function getMaxProductCount(){
        return $this->maxProductCount;
    }
    public function getProductCount(){
        return count($this->products);
    }
    public function getProducts(){
        return $this->products;
    }
    public function isEmpty(){
        return $this->getProductCount() == 0;
    }
    public function isFull(){
        return $this->getProductCount() == $this->maxProductCount;
    }
    public function isPartiallyFull(){
        return !$this->isEmpty() && !$this->isFull();
    }
    public function putProduct(Product $product){
        if ($this->isFull()) {
            throw new \Exception('Cant be added more the shelf is full!');
        }

        $this->products[] = $product;
    }
    public function takeProduct(){
        if ($this->isEmpty()) {
            throw new \Exception('Cant be taken a product from the shelf is empty!');
        }

        return array_pop($this->products);
    }
    public function __toString(){
        $infoArr = [
            'IS FULL' => $this->isFull() ? 'Yes' : 'No',
            'IS PARTLY FULL' => $this->isPartiallyFull() ? 'Yes' : 'No',
            'PRODUCT COUNT' => $this->getProductCount(),
        ];

        $output = '';
        foreach ($infoArr as $key => $val) {
            $output .= "$key: $val\n";
        }

        return $output;
    }
}