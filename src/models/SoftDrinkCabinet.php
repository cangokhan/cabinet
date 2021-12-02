<?php

namespace App\Models;


class SoftDrinkCabinet
{
    // door status options
    const DOOR_STATUS_CLOSED = 0;
    const DOOR_STATUS_OPEN = 1;
    // occupancy status options
    const OCCUPANCY_STATUS_EMPTY = 0;
    const OCCUPANCY_STATUS_PARTLY_FULL = 1;
    const OCCUPANCY_STATUS_FULL = 2;

    private $maxShelfCount;
    private $doorStatus;
    private $shelves = [];
    private $occupancyStatus;

    public function __construct($maxShelfCount){
        $this->doorStatus = self::DOOR_STATUS_CLOSED;
        $this->occupancyStatus = self::OCCUPANCY_STATUS_EMPTY;
        $this->maxShelfCount = $maxShelfCount;
    }

    public function getShelfCount(){
        return $this->maxShelfCount;
    }

    public function getDoorStatus(){
        return $this->doorStatus;
    }

    public function isDoorOpen(){
        return $this->doorStatus == self::DOOR_STATUS_OPEN;
    }

    public function getOccupancyStatus(){
        return $this->occupancyStatus;
    }

    public function isFull(){
        return $this->occupancyStatus == self::OCCUPANCY_STATUS_FULL;
    }

    public function getShelves(){
        return $this->shelves;
    }

    public function isPartlyFull(){
        return $this->occupancyStatus == self::OCCUPANCY_STATUS_PARTLY_FULL;
    }

    public function addShelf(Shelf $shelf){
        if (count($this->shelves) == $this->maxShelfCount) {
            throw new \Exception('You cant add more capacity is full!');
        }

        $this->shelves[] = $shelf;
    }
    public function putProduct(Product $product){
        if (!$this->isDoorOpen()) {
            throw new \Exception('You cant add a new product door is closed!');
        }

        $hasAdded = false;
        foreach ($this->shelves as $shelf) {
            if ($shelf->isFull()) {
                continue;
            }

            $shelf->putProduct($product);
            $hasAdded = true;
            break;
        }

        if ($hasAdded) {
            $this->afterAddedProduct($product);
        } else {
            throw new \Exception("Product {$product->getName()} couldnt be added cabinet is full!");
        }
    }
    public function takeProduct(){

        if (!$this->isDoorOpen()) {
            throw new \Exception('You cant take a product door is closed!');
        }

        $takenProduct = null;
        for ($i = count($this->shelves) - 1; $i >= 0; $i--) {
            $shelf = $this->shelves[$i];
            if ($shelf->isEmpty()) {
                continue;
            }

            $takenProduct = $shelf->takeProduct();
            break;
        }

        if (isset($takenProduct)) {
            $this->afterTakenProduct($takenProduct);
        } else {
            throw new \Exception("Any product couldnt be taken cabinet is empty!");
        }
    }
    public function openDoor(){

        if ($this->isDoorOpen()) {
            throw new \Exception('The door is already open!');
        }

        $this->doorStatus = self::DOOR_STATUS_OPEN;
    }

    /**
     * Closes the door of the cabinet
     * @throws \Exception
     */
    public function closeDoor(){
        if (!$this->isDoorOpen()) {
            throw new \Exception('The door is already closed!');
        }

        $this->doorStatus = self::DOOR_STATUS_CLOSED;
    }
    public function afterAddedProduct(Product $product){
        $this->calculateOccupancyStatus();
    }
    public function afterTakenProduct(Product $product){
        $this->calculateOccupancyStatus();
    }
    protected function calculateOccupancyStatus(){
        $fullShelfCount = 0;
        $hasAddedAnyProducts = false;

        /**
         * calculate counts of full shelves
         * determine if there is any products in the cabine
         */
        foreach ($this->shelves as $shelf) {
            if ($shelf->isFull()) {
                $fullShelfCount++;
            } else if ($shelf->isPartiallyFull()) {
                $hasAddedAnyProducts = true;
            }
        }

        /**
         * determine occupancy status of the cabinet
         */
        if ($fullShelfCount == count($this->shelves)) {
            $this->occupancyStatus = self::OCCUPANCY_STATUS_FULL;
        } else if ($hasAddedAnyProducts) {
            $this->occupancyStatus = self::OCCUPANCY_STATUS_PARTLY_FULL;
        } else {
            $this->occupancyStatus = self::OCCUPANCY_STATUS_EMPTY;
        }
    }
    public static function getOccupancyStatusText($status){
        if ($status == self::OCCUPANCY_STATUS_EMPTY) {
            $text = 'Empty';
        } else if ($status == self::OCCUPANCY_STATUS_PARTLY_FULL) {
            $text = 'Partly Full';
        } else {
            $text = 'Full';
        }

        return $text;
    }
    public function __toString(){
        $infoArr = [
            'DOOR STATUS' => $this->doorStatus == self::DOOR_STATUS_OPEN ? 'Open' : 'Closed',
            'OCCUPANCY STATUS' => self::getOccupancyStatusText($this->occupancyStatus),
            'SHELF COUNT' => count($this->shelves),
        ];

        for ($i = 0; $i < count($this->shelves); $i++) {
            $infoArr['SHELF ' . ($i + 1)] = "\n" . (string)$this->shelves[$i];
        }

        $output = '';
        foreach ($infoArr as $key => $val) {
            $output .= "$key: $val\n";
        }

        return $output;
    }
}

