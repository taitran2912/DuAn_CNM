<?php
include_once("../model/mLogin.php");

class ctrlLogin {
    public function loginUser($username, $pass) {
        $p = new mLogin();
        $result = $p->loginUser($username, $pass);

        if ($result != null) {
            // Trả về đúng mảng chứa id và role
            return [
                "id" => $result["id"],
                "role" => $result["role"]
            ];
        } else {
            echo "<script>alert('Đăng nhập thất bại!!!')</script>";
            return 0;
        }
    }
}
?>