<?php
session_start();
$ketnoi = mysqli_connect("localhost","root","","quanlytruyenonline");
mysqli_set_charset($ketnoi,"utf8mb4");
//$ketnoi->query("set names utf8mb4");
if($_SESSION['tk'] == ''){
    $path = str_replace("/truycap/addtruyen.php","",$_SERVER['PHP_SELF']);
    header("Location: $path/trangchu.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../icon/icon.png" type="image/x-icon">
    <title>Upload</title>
</head>
<style>
    .trove{
        font-size: 30px;
    }
    a{
        text-decoration: none;
    }
    .upload{
        text-align: center;
        font-size: 30px;
        font-weight: bolder;
        text-shadow: 0px 0px 5px blue;
    }
    .theloai{

        margin-top: 20px;
        width: 1000px;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
    }
    .tags{
        display: grid;
        margin: 20px;
        grid-template-columns: auto auto auto auto auto;
    }
    .tags input{
        width: 100px;
        height: 30px;
        margin: 20px;
        border-radius: 10px;
        background-color: wheat;
        color:black;
        font-weight: bold;
    }
    .nutupload{
        border-radius: 20px;
        padding: 20px;
        background-color: chartreuse;
        box-shadow: 0px 0px 50px green;
    }
</style>
<body>
<?php
if(isset($_POST['ten'])){
    if($_POST['ten'] != ""){
        $ten = $_POST['ten'];
        $arrten = explode(" ",$ten);
        $matruyen = "";
        foreach($arrten as $a){
            $matruyen = $matruyen.$a[0];
        }
        $matruyenold = $matruyen;
        while(true){
            
            $kiemtramatruyen = mysqli_query($ketnoi,"SELECT MaTruyen FROM truyen WHERE MaTruyen = '$matruyen'");
            if(mysqli_num_rows($kiemtramatruyen) > 0){
                $matruyen=$matruyenold.rand(0,100);
            }else{
                break;
            }
        }
    //kiểm tra file
        $kiemtrafile = false;
        if(isset($_FILES['chinh'],$_FILES["chapters"])){
            $kiemtrafile = true;
            $total_count = count($_FILES['chapters']['name']);
            if($total_count == 0){
                $kiemtrafile = false;
            }
            for($i = 0; $i < $total_count ; $i++){
                if(basename($_FILES["chapters"]["name"][$i],".jpg") == $i+1){
                    continue;
                }
                $kiemtrafile = false;
                break;
            }
            if(basename($_FILES["chinh"]["name"],".jpg") != "chinh"){
                $kiemtrafile = false;
            }
        }
    //kiểm tra thể loại
            $kiemtratheloai = false;
            $bangtheloai = mysqli_query($ketnoi,"SELECT MaTheLoai FROM theloai");
            while($row = mysqli_fetch_array($bangtheloai)){
                if(isset($_POST['theloai'.$row['MaTheLoai']])){
                    $kiemtratheloai = true;
                    break;
                }
            }
if($kiemtrafile&&$kiemtratheloai){
    //Thêm files chính
        if(!file_exists("../data/truyen/$matruyen")){
            mkdir("../data/truyen/$matruyen");
        }
        $tmpchinh = $_FILES["chinh"]['tmp_name'];
        move_uploaded_file($tmpchinh,"../data/truyen/$matruyen/".$_FILES["chinh"]["name"]);
    //Thêm files chapter
        if(!file_exists("../data/truyen/$matruyen/1")){
            mkdir("../data/truyen/$matruyen/1");
        }
        $total_count = count($_FILES['chapters']['name']);
        for($i = 0; $i < $total_count ; $i++){
            $tmpFilePath = $_FILES['chapters']['tmp_name'][$i];
            if ($tmpFilePath != ""){
                $newFilePath = "../data/truyen/$matruyen/1/" . $_FILES['chapters']['name'][$i];
                move_uploaded_file($tmpFilePath, $newFilePath);
            }
        }
    //Thêm truyện
        mysqli_query($ketnoi,"INSERT INTO truyen(MaTruyen,TenTruyen,TenDangNhap) values('$matruyen','$ten','".$_SESSION['tk']."')");
    //Thêm chapter 1
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        mysqli_query($ketnoi,"INSERT INTO chapters(MaTruyen,Chapter,NgayDang) values('$matruyen',1,'".date("Y-n-j H:i:s")."')");
    //Thêm thuộc thể loại
        $bangtheloai = mysqli_query($ketnoi,"SELECT MaTheLoai FROM theloai");
        while($row = mysqli_fetch_array($bangtheloai)){
            if(isset($_POST['theloai'.$row['MaTheLoai']])){
                mysqli_query($ketnoi,"INSERT INTO thuoctheloai values('$matruyen',".$row['MaTheLoai'].")");
            }
        }
        $path = str_replace("/truycap/addtruyen.php","",$_SERVER['PHP_SELF']);
        header("Location: $path/trangchu.php");
}else if(!$kiemtrafile){
    echo "<script>
    alert('Đăng truyện thất bại! Tên file ảnh chính phải là chinh.jpg và các ảnh chapter 1 phải bắt đầu từ số 1.jpg!!!');
    document.location = document.location;
    </script>";
}else{
    echo "<script>
    alert('Đăng truyện thất bại! Bạn chưa chọn thể loại!!!');
    document.location = document.location;
    </script>";
}
    }else{
        echo "<script>
    alert('Đăng truyện thất bại! Bạn chưa nhập tên!!!');
    document.location = document.location;
    </script>";
    }
}
?>
<a href="../trangchu.php" class="trove"><<< Trở về <<<</a><br>
<div class="upload">UPLOAD TRUYỆN MỚI</div>
<hr>
<br>
<form method="post" align=center enctype='multipart/form-data'>
Tên Truyện : <input type="text" name="ten">
<br>
<hr>
Ảnh chính : <input type="file" name="chinh" accept="image/jpeg"> 
<br>
<hr>
<br>
Thể Loại : 
<br>
<div align=center>
    <div class="theloai">
        <div class="tags">
    <?php
        $ketquatheloai = mysqli_query($ketnoi,"SELECT MaTheLoai,TenTheLoai FROM theloai");
        while($row = mysqli_fetch_array($ketquatheloai)){
            echo "<input type='button' id='tl".$row['MaTheLoai']."' value='".$row['TenTheLoai']."'>
            ";
        }
    ?>
        </div>
    </div>
</div>
<?php
    $ketquatheloai = mysqli_query($ketnoi,"SELECT MaTheLoai,TenTheLoai FROM theloai");
    while($row = mysqli_fetch_array($ketquatheloai)){
    echo "<input type='hidden' name='theloai".$row['MaTheLoai']."' id='hidden".$row['MaTheLoai']."' value='".$row['TenTheLoai']."' disabled>
    ";
    }
?>
<script>
    <?php
    $ketquatheloai = mysqli_query($ketnoi,"SELECT MaTheLoai FROM theloai");
    while($row = mysqli_fetch_array($ketquatheloai)){
        $ma = $row['MaTheLoai'];
        echo "
        document.getElementById('tl$ma').addEventListener('click',theloai$ma);
        function theloai$ma(){
            var tl = document.getElementById('tl$ma');
            var theloai = document.getElementById('hidden$ma');
            if(theloai.disabled){
                tl.style.backgroundColor = 'black';
                tl.style.color = 'aqua';
                theloai.disabled = false;
            }else{
                tl.style.backgroundColor = 'wheat';
                tl.style.color = 'black';
                theloai.disabled = true;
            }
        }
        ";
    }
    ?>
</script>
<hr>Ảnh chapter 1: <input type="file" name="chapters[]" accept="image/jpeg" multiple><hr>
<input type="submit" value="UP!!!" class="nutupload">
</form>
</body>
</html>
