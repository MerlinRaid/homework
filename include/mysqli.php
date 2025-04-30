<?php
class Db {
    private $con;

    public function __construct() {
        $this->con = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($this->con->connect_errno) {
            echo "<strong>Viga andmebaasiga:</strong> " . $this->con->connect_error;
        } else {
            mysqli_set_charset($this->con, "utf8");
        }
    }

    public function dbQuery($sql) {
        $res = mysqli_query($this->con, $sql);
        if ($res === false) {
            echo "<div>Vigane päring: " . htmlspecialchars($sql) . "</div>";
            return false;
        }
        return $res;
    }

    public function dbGetArray($sql) {
        $res = $this->dbQuery($sql);
        if ($res !== false) {
            $data = [];
            while ($row = mysqli_fetch_assoc($res)) {
                $data[] = $row;
            }
            return (!empty($data)) ? $data : false;
        }
        return false;
    }

    public function dbFix($var) {
        if (!$this->con || !($this->con instanceof mysqli)) return "NULL";
        if (is_null($var)) return "NULL";
        if (is_bool($var)) return $var ? '1' : '0';
        if (is_numeric($var)) return $var;
        return "'" . $this->con->real_escape_string($var) . "'";
    }
}
