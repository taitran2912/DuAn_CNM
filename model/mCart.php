<?php
include_once("connect.php");
class mCart{
    public function mCart($str){
        $p = new clsketnoi();
        $conn = $p->moKetNoi();
        $conn->set_charset('utf8');
        if($conn){
            $tbl = $conn->query($str);
            $p->dongKetNoi($conn);
            return $tbl;
        } else {
            return false;
        }
    }
}
?>