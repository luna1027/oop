<h1>Object-Oriented Programming</h1>
<?php
$cat = new Cat('米漿', '玳瑁', '踏踏');
echo $cat->getName();
echo $cat->getColor();
echo $cat->getCute();
// $cat=new Animal();
// $cat->run();

// echo 

// Class + 類別
class Animal
{
    // 權限 $屬性 = 宣告內容
    protected $type = 'animal';
    protected $name = 'John';
    protected $hair_color = "brown";

    // public function __construct()
    public function __construct($type, $name, $color)
    // public function __construct($name, $color)
    {
        //建構式內容
        $this->run();
        // $this->type = $type;
        // $this->name = $name;
        // $this->hair_color = $color;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getColor()
    {
        return $this->hair_color;
    }

    public function getCute()
    {
        return $this->cute;
    }

    public function run()
    {
        //公開行為內容
        echo "RUN !";
        // 在公開行為裡去執行私有的方法
        // $this->speed();
        echo $this->name;
        // echo $this->cute; // 會叫不到，因為宣告在 class Cat 內
    }

    private function speed()
    {
        //私有行為內容
        echo "SHOUT !";
    }
}

class Cat extends Animal
{
    // 放宣告在 class Cat 內，父層 class Animal 會叫不到，平層 class Dog 也叫不到
    public $cute;
    public function __construct($name, $color, $cute)
    {
        // parent::__construct($name, $color); // 先執行完建構式的東西
        // $this->type = '貓';

        $this->name = $name;
        $this->hair_color = $color;
        $this->type = '貓';
        $this->cute = $cute;
    }
}

class Dog extends Animal
{
    public function __construct($name, $color)
    {
        $this->name = $name;
        $this->hair_color = $color;
        $this->type = '狗';
    }
}
echo "<hr>";
$car = new Car(55);
// echo $car->getColor();
echo $car->addColor('Green')->addColor('紫色')->addColor('Orange')->getColor();

class Car
{
    protected $color = 'Yellow';
    public function __construct($color)
    {
        $this->color = $color;
    }
    function getColor()
    {
        return $this->color;
    }

    function addColor($color){
        $this->color=$this->color."+".$color;
        return $this;

    }
}
?>