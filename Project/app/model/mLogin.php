<?php
   require_once("connect.php");

   class mLogin {
       private $conn;
   
       public function __construct() {
           $db = new connection();
           $this->conn = $db->getConnection(); // lấy kết nối
       }
   
       public function loginUser($user, $pass) {
           $sql = "SELECT id, role FROM users WHERE (email = ? OR phone = ?) AND password = ?";
           $stmt = $this->conn->prepare($sql);
           $stmt->bind_param("sss", $user, $user, $pass);
           $stmt->execute();
           $result = $stmt->get_result();
           if ($result->num_rows > 0) {
               return $result->fetch_assoc(); // trả về mảng
           }
           return null;
       }
   }
   
?>