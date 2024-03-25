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
.login{
    font-size: 35px;
}
.dangky{
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
    
    flex: 3;
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
            <th class="login">Đăng nhập</th>
            <td ><a href="dangky.php" class="dangky">Đăng ký</a></td>
        </tr>
        <tr>
            <th colspan="3"><hr></th>
        </tr>
        <tr>
            <th colspan="3">
                <div class="nhap">
                    <div class="div1">Tài khoản: </div>
                    <div class="div2"><input type="text" name="tk"></div>
                </div>
            </th>
        </tr>
        <tr>
            <th colspan="3">
                <div  class="nhap">
                    <div class="div1">Password:</div>
                    <div class="div2"><input type="password" name="mk"></div>
                </div>
            </th>
        </tr>
        <tr>
            <th colspan="3"><hr></th>
        </tr>
        <tr>
            <th colspan="3">
<?php
if(isset($_POST['tk'])){
    $tk = $_POST['tk'];
    $mk = $_POST['mk'];
    if($tk == '' || $mk == ''){
        echo "Mời điền đầy đủ thông tin !!!";
    }else {
        try {
            $kn = mysqli_connect("localhost","root","","quanlytruyenonline");
                mysqli_set_charset($kn,"utf8");
                $r = mysqli_query($kn,"SELECT * FROM taikhoan WHERE TenDangNhap = '$tk' AND MatKhau = '$mk'");
                if(mysqli_num_rows($r) > 0){
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
                    
                }else{
                    echo "Tài khoản hoặc Password không đúng !!!";
                }
        } catch (\Throwable $th) {
            
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
            <th colspan="3"><input type="submit" value="Đăng nhập"></th>
        </tr>
        </form>
    </table>
</body>
</html>