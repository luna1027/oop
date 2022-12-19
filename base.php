<?php

$Student = new DB('students');
$Dept = new DB('dept');
$Scores = new DB('student_scores');

// echo $Dept->find(2)->name;
// $Student->save(['id' => 1, 'uni_id' => 'A123123456', 'school_num' => 12345]);
// $Student->save(['uni_id' => 'A123123456', 'school_num' => 12345]);
// $rows = insert('students', ['id' => 5555,'school_num' => 12345]);
// $Student->del(['school_num' => 12345]);


function prr($Arr)
{
    echo "<pre>";
    print_r($Arr);
    echo "</pre>";
}

// $john = $Student->find(30);
// echo is_object($john);
// prr($john);
// echo $john->name;
// echo $john->parents;

echo "<br>";
$stus = $Student->all(['dept' => 3]);
// foreach ($stus as $stu) {
// echo $stu['parents'] . "=>" . $stu['dept'];
// echo "<br>";
// }

// Math Function //
// count sum max min avg
// echo $Student->count(['dept' => 5, 'graduate_at' => 1]);
// echo $Student->sum('dept', ['dept' => 2]);
// echo $Student->max('school_num');
// echo $Student->min('school_num');
// echo $Scores->avg('score');
// echo $Scores->smma('max', 'score');
// echo "<br>";
// echo $Scores->smma('min', 'score');
// echo "<br>";
// echo $Scores->smma('sum', 'score');
// echo "<br>";
// echo $Scores->smma('avg', 'score');
// echo "<br>";
// echo $Scores->smma('count', 'score');


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

    protected function arrayToSqlArray($eachs)
    {
        foreach ($eachs[0] as $key => $value) {
            $each[] = "`$key`='$value'";
        }
        return $each;
        // return join("")
    }

    public function all(...$args) // function all($table, ...$args){
    {
        // global $pdo;
        $sql = "SELECT * FROM `$this->table` ";  // $sql = "SELECT * FROM `$table` ";

        if (isset($args)) {
            // prr($args);
            if (is_array($args[0])) {
                // foreach ($args[0] as $key => $value) {
                //     $tmp[] = "`$key`='$value'";
                // }
                // prr($tmp);
                $sql = $sql . " WHERE " . join(" AND ", $this->arrayToSqlArray($args));
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
            // foreach ($factor as $key => $value) {
            //     $find[] = "`$key`='$value'";
            // }
            $sql .= " WHERE " . join(" AND ", $this->arrayToSqlArray($factor));
        } else {
            $sql .= " WHERE `id` = '$factor'";
        }
        // echo $sql;
        // return $this->$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
        $row = $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
        // var_dump($row);
        // prr($row);
        // echo gettype($row);
        $date = new stdClass;
        foreach ((array)$row as $col => $value) {
            $date->{$col} = $value;
        }

        // prr($date);
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

    // Insert & Update
    public function save($save)
    {
        if (isset($save['id'])) {
            // Update
            // // Can't be Constraint
            $id = $this->arrayToSqlArray($save);
            unset($save['id']);
            // foreach ($save as $key => $value) {
            //     if ($key !== 'id') {
            //         $saveSet[] = "`$key`='$value'";
            //     }
            // }
            // prr($saveSet);
            $sql = " UPDATE `$this->table` SET " . join(",", $id) . " WHERE `id` = " . $save['id'];
        } else {
            // Insert
            $keys = [];
            $values = [];
            foreach ($save as $key => $value) {
                $keys[] = "`$key`";
                $values[] = "'$value'";
            }
            $sql = "INSERT INTO `$this->table` (" . join(",", $keys) . ") VALUES (" . join(",", $values) . ")";
        }
        echo $sql;
        return $this->pdo->exec($sql);
    }

    public function del(...$args)
    {
        // global $pdo;
        $sql = "DELETE FROM `$this->table` ";

        if (isset($args)) {
            if (is_array($args[0])) {
                // $tmp = [];
                // foreach ($args[0] as $key => $value) {
                //     $tmp[] = "`$key`='$value'";
                // }
                $sql = $sql . " WHERE " . join(" AND ", $this->arrayToSqlArray($args[0]));
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
        // return $this->pdo->exec($sql);
        return $this->pdo->prepare($sql)->execute();
    }

    // // Math // //
    // Count
    function count($count, ...$args)
    {
        return $this->smma('count', $count, ...$args);
        // if (is_array($count)) {
        //     foreach ($count as $key => $value) {
        //         $countWhere[] = "`$key`='$value'";
        //     }
        //     $sql = " SELECT COUNT(*) FROM `$this->table` WHERE " . join(" AND ", $countWhere);
        // } else {
        //     $sql = " SELECT COUNT(*) FROM `$this->table` WHERE " . $count;
        // }
        // echo $sql;
        // return $this->pdo->query($sql)->fetchColumn();
    }

    // Sum
    function sum($sum, $ss, ...$args)
    {
        return $this->smma('sum', $sum, ...$args);
        // if (isset($args[0])) {
        //     foreach ($args[0] as $key => $value) {
        //         $sumWhere[] = "`$key`='$value'";
        //     }
        //     $sql = "SELECT SUM(`$sum`) FROM `$this->table` WHERE " . join(" AND ", $sumWhere);
        // } else {
        //     $sql = "SELECT SUM(`$sum`) FROM `$this->table`";
        // }
        // echo $sql;
        // return $this->pdo->query($sql)->fetchColumn();
    }

    // Max
    function max($max, ...$args)
    {
        return $this->smma('max', $max, ...$args);
        // if (isset($args[0])) {
        //     foreach ($args[0] as $key => $value) {
        //         $maxWhere[] = "`$key`='$value'";
        //     }
        //     $sql = "SELECT MAX(`$max`) FROM `$this->table` WHERE " . join(" AND ", $maxWhere);
        // } else {
        //     $sql = "SELECT MAX(`$max`) FROM `$this->table`";
        // }
        // echo $sql;
        // return $this->pdo->query($sql)->fetchColumn();
    }

    // Min
    function min($min, ...$args)
    {
        return $this->smma('min', $min, ...$args);
        // if (isset($args[0])) {
        //     foreach ($args[0] as $key => $value) {
        //         $minWhere[] = "`$key`='$value'";
        //     }
        //     $sql = "SELECT MIN(`$min`) FROM `$this->table` WHERE " . join(" AND ", $minWhere);
        // } else {
        //     $sql = "SELECT MIN(`$min`) FROM `$this->table`";
        // }
        // echo $sql;
        // return $this->pdo->query($sql)->fetchColumn();
    }

    // Avg
    function avg($avg, ...$args)
    {
        return $this->smma('AVG', $avg, ...$args);
        // $this->smma;
        // if (isset($args[0])) {
        //     foreach ($args[0] as $key => $value) {
        //         $avgWhere[] = "`$key`='$value'";
        //     }
        //     $sql = "SELECT AVG(`$avg`) FROM `$this->table` WHERE " . join(" AND ", $avgWhere);
        // } else {
        //     $sql = "SELECT AVG(`$avg`) FROM `$this->table`";
        // }
        // echo $sql;
        // return $this->pdo->query($sql)->fetchColumn();
    }

    // Count + Sum + Max + Min + Avg
    protected function smma($smma, $ss, ...$args)
    {
        if ($smma = 'count') {
            $sql = "SELECT $smma(*) FROM `$this->table`";
        } else {
            $sql = "SELECT $smma(`$ss`) FROM `$this->table`";
        }

        if (isset($args[0])) {
            if (is_array($args[0])) {
                // foreach ($args[0] as $key => $value) {
                //     $smmaWhere[] = "`$key`='$value'";
                // }
                $sql .= join(" AND ", $this->arrayToSqlArray($args)[0][0]);
            } else {
                $sql .= $args;
            }
        }

        echo $sql;
        return $this->pdo->query($sql)->fetchColumn();
    }

    // Free 
    function free($sql)
    {
        $dsn = "mysql:host=localhost;charest=utf8;dbname=school";
        $pdo = new PDO($dsn, 'root', '');
        return $pdo->query($sql)->fetchAll();
    }
}
