<?php

$student = new DB('students');
// $student->count();
// count($table);
// var_dump($student);
function dd($array)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

// echo "<pre>";
// print_r($student->all(['id'=>5,'id'=>100]));
// // print_r($student->all());
// echo "</pre>";

// dd($student->all(['graduate_at' => 2, 'dept' => 2]));
// $stus=$student->all([ 'id' => 200 , 'id' => 100 ]);
// foreach($stus as $stu){
// echo $stu['tel'];
// }

// dd($student->insert(['name' => '下大雨', 'graduate_at' => 2, 'dept' => 2]));
dd($student->del(['name' => '下大雨']));


class DB
{
    protected $table;
    protected $dsn = "mysql:host=localhost;charest=utf8;dbname=school";
    protected $pdo;

    public function __construct($table)
    {
        $this->pdo = new PDO($this->dsn, 'root', '');
        $this->table = $table;
    }

    public function all(...$args) // function all($table, ...$args){
    {
        // global $pdo;
        $sql = "SELECT * FROM `$this->table` ";  // $sql = "SELECT * FROM `$table` ";

        if (isset($args)) {
            // dd($args);
            if (is_array($args[0])) {
                foreach ($args[0] as $key => $value) {
                    $tmp[] = "`$key`='$value'";
                }
                // dd($tmp);
                $sql = $sql . " WHERE " . join(" AND ", $tmp);
            } else {
                $sql = $sql . $args[0];
            }
        }

        if (isset($args[1])) {
            $sql = $sql . $args[1];
        }

        echo $sql;
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($insert)
    {
        // global $pdo;
        $sql = "INSERT INTO `$this->table` ";
        if (is_array($insert)) {
            dd($insert);
            $keys = [];
            $values = [];
            foreach ($insert as $key => $value) {
                $keys[] = "`$key`";
                $values[] = "'$value'";
            }
            $sql = $sql . "(" . join(",", $keys) . ") VALUES (" . join(",", $values) . ")";
        } else {
            // $sql = $sql . $insert;
            $sql .= $insert;
        }
        echo $sql;
        return $this->pdo->exec($sql);
    }

    public function del(...$args)
{
    global $pdo;
    $sql = "DELETE FROM `$this->table` ";

    if (isset($args)) {
        if (is_array($args[0])) {
            $tmp = [];
            foreach ($args[0] as $key => $value) {
                $tmp[] = "`$key`='$value'";
            }
            $sql = $sql . " WHERE " . join(" AND ", $tmp);
        } 
        // elseif (is_numeric($args[0])) {
        //     print_r($args[0]);
        //     $sql = $sql . " LIMIT " . $args[0];
        //     // echo $sql;
        // } 
        else {
            // 是字串
            $sql .= " WHERE `id`='{$args[0]}'";
        }
    }
    echo $sql;
    return $this->pdo->exec($sql);
}
}
