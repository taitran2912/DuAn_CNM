<?php
class connection {
    private $con;

    public function __construct() {
        $this->con = mysqli_connect("localhost", "root", "", "nongsan");
        mysqli_set_charset($this->con, 'utf8');
    }

    public function getConnection() {
        return $this->con;
    }

    public function close() {
        mysqli_close($this->con);
    }
}
?>
