<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="../icon/icon.png" type="image/x-icon">
    <title>Đăng nhập</title>
</head>
<style>
body{
    background: hsla(0, 0%, 0%, 1);
    color:rgb(0, 255, 21);
    width: clamp(1000px,100%,100vw);
}
table {
    width: 30rem;
    height: 20rem;
    box-shadow: 0 0 1rem 0 rgb(255, 255, 255); 
    border-radius: 5px;
    position: relative;
    z-index: 1;
    background: inherit;
    overflow: hidden;
    margin-top: 100px;
}

table:before {
    content: "";
    position: absolute;
    background: inherit;
    z-index: -1;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    box-shadow: inset 0 0 2000px rgba(255, 255, 255, 0.5);
    filter: blur(10px);
    margin: -20px;
}
.icon{
    height: 50px;
    margin-left: 20px;
}
.dangky{
    font-size: 35px;
}
.login{
    color: aquamarine;
}
.nhap{
    display: flex;
}
.div1{
    flex: 1;
    margin-left: 50px;
    width: 150px;
}
.div2{
    flex: 2;
}
.div3{
    flex: 1;
    margin-left: 50px;
    width: 150px;
}
.canhbao{
    color: red;
    font-size: 16px;
}
a{
    text-decoration: none;
}
</style>
<body>
    <table align="center">
        <form method="post">
        <tr>
            <td><a href="../trangchu.php"><img src="../icon/icon.png" alt="trangchu" class="icon"></a></td>
            <th class="dangky">Đăng ký</th>
            <td ><a href="dangnhap.php" class="login">Đăng nhập</a></td>
        </tr>
        <tr>
            <th colspan="3"><hr></th>
        </tr>
        <tr>
            <th colspan="3">
                <div class="nhap">
                    <div class="div1">Tài khoản: </div>
                    <div class="div2"><input type="text" name="tk" id="tk"></div>
                </div>
            </th>
        </tr>
        <tr>
            <th colspan="3">
                <div  class="nhap">
                    <div class="div1">Password:</div>
                    <div class="div2"><input type="password" name="mk" id="mk"></div>
                </div>
            </th>
        </tr>
        <tr>
            <th colspan="3">
                <div  class="nhap">
                    <div class="div3">Nhập lại Password:</div>
                    <div class="div2"><input type="password" name="mk2" id="mk2"></div>
                </div>
            </th>
        </tr>
        <tr>
            <th colspan="3"><hr></th>
        </tr>
        <tr>
            <th colspan="3" class="canhbao">
<?php
    if(isset($_POST['tk'])){
        $tk = $_POST['tk'];
        $mk = $_POST['mk'];
        $mk2 = $_POST['mk2'];
        if($mk == "" || $tk == "" || $mk2 == ""){
            echo "Mời điền đầy đủ thông tin!!!";
        }else if($mk != $mk2){ 
            echo "Mật khẩu nhập lại không đúng !!!";
        }else{
            try {
                $kn = mysqli_connect("localhost","root","","quanlytruyenonline");
                mysqli_set_charset($kn,"utf8");
                $r = mysqli_query($kn,"SELECT * FROM taikhoan WHERE TenDangNhap = '$tk'");
                if(mysqli_num_rows($r) > 0){
                    echo "Tài khoản đã tồn tại";
                }else{
                    mysqli_query($kn,"INSERT INTO taikhoan values('$tk','$mk')");
                    session_start();
                    $_SESSION['tk'] = $tk;
                    mysqli_close($kn);
                    if(isset($_GET['doc'])){
                        header("Location: ".str_replace(basename($_SERVER['PHP_SELF']),"",$_SERVER['PHP_SELF'])."doc.php?truyen=".$_GET['truyen']."&chap=".$_GET['chap']);
                    }else if(isset($_GET['trangtruyen'])){
                        header("Location: ".str_replace(basename($_SERVER['PHP_SELF']),"",$_SERVER['PHP_SELF'])."truyen.php?truyen=".$_GET['truyen']);
                    }else{
                        header("Location: ".str_replace("truycap/".basename($_SERVER['PHP_SELF']),"",$_SERVER['PHP_SELF'])."trangchu.php");
                    }
                }
            } catch (\Throwable $th) {
                echo "Không thể kết nối với MYSQL !!!";
            }  
        }
    }else{
        echo "---";
    }
?>
            </th>
        </tr>
        <tr>
            <th colspan="3"><hr></th>
        </tr>
        <tr>
            <th colspan="3"><input type="submit" value="Đăng ký"></th>
        </tr>
        </form>
    </table>
</body>
</html>