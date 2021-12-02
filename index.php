<?php
ini_set("display_errors",1);

require_once 'func.php';

use App\models\Product;
use App\CabinetBuilder;

try {
    $maxSelfCount = 3;
    $eachShelfMaxProductCount = 20;

    // build cabinet
    $cabinetBuilder = new CabinetBuilder($maxSelfCount);

    // create product shelves for the cabinet
    $cabinetBuilder->produceShelves($eachShelfMaxProductCount);

    // get the built cabinet
    $cabinet = $cabinetBuilder->getSoftDrinkCabinet();

    $cabinet->openDoor();

    // add 50 products into the cabinet
    for ($i = 1; $i <= 50; $i++) {
        $productName = 'Juice - ' . $i;
        $product = new Product($productName);
        $cabinet->putProduct($product);
    }

    // show cabinet status
    echo "\nADDED SOME PRODUCTS\n";
    echo "CABINET STATUS : ";
    echo $cabinet;

    // take out 20 products from the cabinet
    for ($i = 1; $i <= 20; $i++) {
        $cabinet->takeProduct();
    }

    $cabinet->closeDoor();

    // show cabinet status
    echo "TAKE PRODUCT";
    echo "CABINET STATUS:";
    echo $cabinet;

} catch (\Exception $exception) {
    echo "\nERROR: " . $exception->getMessage() . "\n";
}