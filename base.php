<?php

$Student = new DB('students');
$Dept = new DB('dept');
$Scores = new DB('student_scores');

function prr($Arr)
{
    echo "<pre>";
    print_r($Arr);
    echo "</pre>";
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

    protected function arrayToSqlArray($eachs)
    {
        foreach ($eachs[0] as $key => $value) {
            $each[] = "`$key`='$value'";
        }
        return $each;
    }

    public function all(...$args)
    {
        $sql = "SELECT * FROM `$this->table` ";
        if (isset($args)) {
            if (is_array($args[0])) {
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
        $sql = "SELECT * FROM `$this->table` ";
        if (is_array($factor)) {
            $sql .= " WHERE " . join(" AND ", $this->arrayToSqlArray($factor));
        } else {
            $sql .= " WHERE `id` = '$factor'";
        }
        $row = $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        $date = new stdClass;
        foreach ((array)$row as $col => $value) {
            $date->{$col} = $value;
        }
        return $date;
    }

    public function insert($insert)
    {
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
        $sql = "DELETE FROM `$this->table` ";
        if (isset($args)) {
            if (is_array($args[0])) {
                $sql = $sql . " WHERE " . join(" AND ", $this->arrayToSqlArray($args[0]));
            } else {
                $sql .= " WHERE `id`='{$args[0]}'";
            }
        }
        echo $sql;
        return $this->pdo->prepare($sql)->execute();
    }

    // // Math // //
    // Count
    function count($count, ...$args)
    {
        return $this->smma('count', $count, ...$args);
    }

    // Sum
    function sum($sum, $ss, ...$args)
    {
        return $this->smma('sum', $sum, ...$args);
    }

    // Max
    function max($max, ...$args)
    {
        return $this->smma('max', $max, ...$args);
    }

    // Min
    function min($min, ...$args)
    {
        return $this->smma('min', $min, ...$args);
    }

    // Avg
    function avg($avg, ...$args)
    {
        return $this->smma('AVG', $avg, ...$args);
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
                $sql .= join(" AND ", $this->arrayToSqlArray($args)[0][0]);
            } else {
                $sql .= $args;
            }
        }

        echo $sql;
        return $this->pdo->query($sql)->fetchColumn();
    }

}

// Free 
function free($sql)
{
    $dsn = "mysql:host=localhost;charest=utf8;dbname=school";
    $pdo = new PDO($dsn, 'root', '');
    return $pdo->query($sql)->fetchAll();
}