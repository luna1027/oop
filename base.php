<?php

$Student = new DB('students');
$Dept = new DB('dept');

echo $Dept->find(2)->name;

function prr($Arr)
{
    echo "<pre>";
    print_r($Arr);
    echo "</pre>";
}

$john = $Student->find(30);
echo is_object($john);
// prr($john);
// echo $john->name;
// echo $john->parents;

echo "<br>";
$stus = $Student->all(['dept' => 3]);
foreach ($stus as $stu) {
    // echo $stu['parents'] . "=>" . $stu['dept'];
    echo "<br>";
}


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
            // prr($args);
            if (is_array($args[0])) {
                foreach ($args[0] as $key => $value) {
                    $tmp[] = "`$key`='$value'";
                }
                // prr($tmp);
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

    // Find
    public function find($factor)
    {
        // global $pdo;
        $sql = "SELECT * FROM `$this->table` ";
        if (is_array($factor)) {
            foreach ($factor as $key => $value) {
                $find[] = "`$key`='$value'";
            }
            $sql .= " WHERE " . join(" AND ", $find);
        } else {
            $sql .= " WHERE `id` = '$factor'";
        }
        echo $sql;
        // return $this->$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
        $row = $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
        $date = new stdClass;
        foreach ($row as $col => $value) {
            $date->{$col} = $value;
        }
        return $date;
    }

    public function insert($insert)
    {
        // global $pdo;
        $sql = "INSERT INTO `$this->table` ";
        if (is_array($insert)) {
            prr($insert);
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
