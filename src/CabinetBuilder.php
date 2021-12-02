<?php

namespace App;

use App\models\Shelf;
use App\models\SoftDrinkCabinet;

class CabinetBuilder
{
    private $softDrinkCabinet;
    private $maxShelfCount;

    public function __construct($maxShelfCount)
    {
        $this->maxShelfCount = $maxShelfCount;
        $this->reset();
    }

    /**
     * Resets the cabinet
     */
    public function reset()
    {
        $this->softDrinkCabinet = new SoftDrinkCabinet($this->maxShelfCount);
    }

    /**
     * Produces the shelves of the cabinet
     * @param $eachShelfMaxProductCount
     */
    public function produceShelves($eachShelfMaxProductCount)
    {
        $i = 1;
        while ($i <= $this->maxShelfCount) {
            $shelf = new Shelf($eachShelfMaxProductCount);
            $this->softDrinkCabinet->addShelf($shelf);
            $i++;
        }
    }

    /**
     * Gets the cabinet
     * @return mixed
     */
    public function getSoftDrinkCabinet()
    {
        return $this->softDrinkCabinet;
    }
}