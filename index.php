<h1>Object-Oriented Programming</h1>
<?php
// // 將類別實體化 // //
// new 實體化 => extends
// Animal 類別
// $cat = new Animal;
// $dog = new Animal;

// echo "<pre>";
// echo var_dump($cat);
// echo "</pre>";

// ** Public ** //
// echo $cat->type;
// echo $cat->name;
// echo $cat->animal . "隻";
// echo $cat->age . "歲";
// echo "<br>";
// echo $dog->type;
// echo $dog->hair_color;
// * 權限為 public 時可以外部修改 * //
// $cat->age = "20";
// echo $cat->age . "歲";

// ** 方法的使用 ** //
// $cat = new Animal;
// $dog = new Animal;

// $cat->run();
// $cat->speed(); // Call to private method Animal::speed() from global scope in 
//                     => 在全域的區域呼叫私有的方法(手法、面向)是不可以的
// echo $cat->name;
// echo $cat->addr;

// ** 建構式 ** //
$cat = new Animal('兒子','米ㄉㄟˊ','虎斑');
echo $cat->getType();
echo $cat->getName();
echo $cat->getColor();
// * 權限為 protected 時不能外部修改 * //
$cat->type='pig';
echo $cat->getType();



// Class + 類別
class Animal
{
    // 權限 $屬性 = 宣告內容
    // public $type = 'animal';
    // public $name = 'John';
    // public $hair_color = "brown";
    // public $animal = '貓*2';
    // public $age = 1 * 3;
    // public $tall;
    protected $type = 'animal';
    protected $name = 'John';
    protected $hair_color = "brown";
    private $addr = '淡水';

    public function __construct($type,$name,$color)
    {
        //建構式內容
        // $this->run();
        $this->type=$type;
        $this->name=$name;
        $this->hair_color=$color;
    }

    public function getType(){
        return $this->type;
    }

    public function getName(){
        return $this->name;
    }

    public function getColor(){
        return $this->hair_color;
    }

    public function run()
    {
        //公開行為內容
        echo "RUN !";
        // 在公開行為裡去執行私有的方法
        $this->speed();
        echo $this->name;
    }

    private function speed()
    {
        //私有行為內容
        echo "SHOUT !";
    }
}




?>