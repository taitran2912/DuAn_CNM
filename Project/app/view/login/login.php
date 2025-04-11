<div class="middle-box text-center loginscreen animated fadeInDown">
    <div>
        <div>

            <h1 class="logo-name">logo</h1>

        </div>

        <form class="m-t" method="POST" name="fm_login">
            <div class="form-group">
                <input type="email" name="txtUser" class="form-control" placeholder="Phone/Email" required="">
            </div>
            <div class="form-group">
                <input type="password" name="txtPass" class="form-control" placeholder="Password" required="">
            </div>
            <button type="submit" name="btnSubmit" class="btn btn-primary block full-width m-b">Login</button>

            <a href="#"><small>Forgot password?</small></a>
            <p class="text-muted text-center"><small>Do not have an account?</small></p>
            <a class="btn btn-sm btn-white btn-block" href="#">Create an account</a>
        </form>
        <!-- <p class="m-t"> <small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small> </p> -->
    </div>
</div>


<?php
    if (isset($_REQUEST["btnSubmit"])) {
        include_once("../controller/ctrl_login.php");
        $ctrl = new ctrlLogin();
        $thongtin = $ctrl->loginUser($_REQUEST["txtUser"], $_REQUEST["txtPass"]);

        if ($thongtin != 0) {
            $_SESSION["id"] = $thongtin["id"];
            $_SESSION["role"] = $thongtin["role"];
            
            // Điều hướng theo vai trò
        switch ($_SESSION["role"]) {
                case '0':
                    header("Location: ../../app/view/admin/dashboard.php");
                    break;
                case '1':
                    header("Location: ../../app/view/staff/home.php");
                    break;
                case '2':
                    header("Location: ../../app/view/customer/home.php");
                    break;
                default:
                    header("Location: ../../app/view/unknown-role.php");
                    break;
            }
            exit();
        } else {
            echo "<script>alert('Đăng nhập thất bại!');</script>";
        }
    }
    
    
?>

