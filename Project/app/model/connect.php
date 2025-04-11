<?php
class connection{
    public function connection_aborted(){

        $con = mysqli_connect("localhost", "root", "", "nongsan");
        mysqli_set_charset($con,'utf8');
        return $con;
    }
    public function close($con){
        mysqli_close($con);
    }
}
?>