<?php

echo Car::$type;
// echo Car::speed();

// $car = new Car;
// echo $car->type;

$car = new Car;
// echo $car->speed();
echo Car::YEAR;

class Car
{
    // static 靜態屬性
    public static $type = 'Luxgen';
    public const YEAR = '2022';

    public static function speed(){
        return 60;
    }
}
