<?php
function rmdir_recurse($path) {
    $path = rtrim($path, '/') . '/';
    $handle = opendir($path);
    while (false !== ($file = readdir($handle))) {
    if($file != '.' and $file != '..' ) {
    $fullpath = $path.$file;
    if (is_dir($fullpath)) rmdir_recurse($fullpath);
    else unlink($fullpath);
    }
    }
    closedir($handle);
    rmdir($path);
}
session_start();

$ketnoi = mysqli_connect("localhost","root","","quanlytruyenonline");
mysqli_set_charset($ketnoi,"utf8");
$truyen = "";
if(isset($_GET['truyen'])){
    $truyen = $_GET['truyen'];
}else{
    $path = str_replace("/truycap/truyen.php","",$_SERVER['PHP_SELF']);
    header("Location: $path/trangchu.php");
}
if(isset($_POST['out'])){
    $_SESSION['tk'] = '';
    header("Location: ".$_SERVER['PHP_SELF']."?truyen=$truyen");
}
if(isset($_POST['xoa'])){
    // Xóa bình luận
    $bangchap = mysqli_query($ketnoi,"SELECT MaChapter FROM chapters WHERE MaTruyen = '$truyen'");
    while($row = mysqli_fetch_array($bangchap)){
        $machap = $row['MaChapter'];
        mysqli_query($ketnoi,"DELETE FROM binhluan WHERE MaChapter = '$machap'");
    }
    // Xóa thuoctheloai
    mysqli_query($ketnoi,"DELETE FROM thuoctheloai WHERE MaTruyen = '$truyen'");
    // Xóa chapters
    mysqli_query($ketnoi,"DELETE FROM chapters WHERE MaTruyen = '$truyen'");
    // Xóa truyen
    mysqli_query($ketnoi,"DELETE FROM truyen WHERE MaTruyen = '$truyen'");
    // Xóa folder
    Rmdir_recurse("../data/truyen/$truyen");
    $path = str_replace("/truycap/truyen.php","",$_SERVER['PHP_SELF']);
    header("Location: $path/trangchu.php");
}
if(isset($_GET['tacgia'])){
    $tg = $_GET['tacgia'];
    mysqli_query($ketnoi,"UPDATE truyen SET TacGia = '$tg' WHERE MaTruyen = '$truyen'");
    header("Location: ".$_SERVER['PHP_SELF']."?truyen=$truyen");
}
if(isset($_GET['mota'])){
    $mt = $_GET['mota'];
    mysqli_query($ketnoi,"UPDATE truyen SET MoTaTruyen = '$mt' WHERE MaTruyen = '$truyen'");
    header("Location: ".$_SERVER['PHP_SELF']."?truyen=$truyen");
}
$tentruyen ="";
$sochap="";
$mota = "";
$nguoidang = "";
$tacgia = "";
$luotxem = "";
$bangtruyen =mysqli_query($ketnoi,"SELECT * FROM truyen WHERE MaTruyen = '$truyen'");
while($row=mysqli_fetch_array($bangtruyen)){
    $nguoidang = $row['TenDangNhap'];
    $mota = $row['MoTaTruyen'];
    $tentruyen = $row['TenTruyen'];
    $tacgia = $row['TacGia'];
    $luotxem = $row['LuotXem'];
}
$ngaydang = [];
$bangchapters =mysqli_query($ketnoi,"SELECT * FROM chapters WHERE MaTruyen = '$truyen' ORDER BY Chapter");
while($row=mysqli_fetch_array($bangchapters)){
    $sochap = $row['Chapter'];
    $ngaydang[] = $row['NgayDang'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="../icon/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="tieude.css">
    <title><?=$_GET['truyen']?></title>
    <?=($_SESSION['tk'] == $nguoidang)?"<script src='capnhap.js'></script>":"" ?>
</head>

<body>
    <?php
//kiểm tra thêm chap mới
if(isset($_FILES['chapters'])){
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
    // thêm chap mới
    if($kiemtrafile){
        $bangmaxchap = mysqli_query($ketnoi,"SELECT MAX(Chapter) as Chapter FROM chapters WHERE MaTruyen = '$truyen'");
        $max = "";
        while($row = mysqli_fetch_array($bangmaxchap)){
            $max = $row['Chapter'];
        }
        $max = $max+1;
        if(!file_exists("../data/truyen/$truyen/$max")){
            mkdir("../data/truyen/$truyen/$max");
        }
        $total_count = count($_FILES['chapters']['name']);
        for($i = 0; $i < $total_count ; $i++){
            $tmpFilePath = $_FILES['chapters']['tmp_name'][$i];
            if ($tmpFilePath != ""){
                $newFilePath = "../data/truyen/$truyen/$max/" . $_FILES['chapters']['name'][$i];
                move_uploaded_file($tmpFilePath, $newFilePath);
            }
        }
        //insert chap
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        mysqli_query($ketnoi,"INSERT INTO chapters(MaTruyen,Chapter,NgayDang) values('$truyen',$max,'".date("Y-n-j H:i:s")."')");
        header("Location: ".$_SERVER['PHP_SELF']."?truyen=$truyen");
    }else{
        echo "<script>
        alert('Đăng chapter mới thất bại! File không hợp lệ!!!');
        document.location = document.location;
        </script>";
    }
    
}?>
    <div class='header'>
        <div class='h1'><a href="../trangchu.php"><img src="../icon/icon.png" class='icon'></a></div>
        <a class='h2' href="../trangchu.php">Truyện Tranh ONLINE</a>
        <?php
            if($_SESSION['tk'] == ""){
                echo "
                <div class='h3'><a href='dangnhap.php?trangtruyen=&truyen=$truyen' class='dn'>Login</a></div>
                <div class='h4'><a href='dangky.php?trangtruyen=&truyen=$truyen' class='dn'>Đăng ký</a></div>";
            }else{
                echo "
                <div class='h3'><h2>".$_SESSION['tk']."</h2></div>
                <div class='h4'><a href='truyen.php?out=&truyen=$truyen' class='dn'>Đăng xuất</a></div>";
            }
        ?>
    </div>
<style>
.main{
    margin-top: 50px;
    width: 1000px;
}
.canhgiua{
    display: flex;
    justify-content: center;
    align-items: center;
}
.tentruyen{
    font-size: 25px;
    color:chartreuse;
}
hr{
    width: 900px;
    border-color: gray;
}
.gom{
    padding-left: 100px ;
}
.hienthichuong{
    width: 700px;
    border: 3px solid wheat;
    padding-bottom: 30px;
    padding-top: 30px;
}
tab{
    padding-left:200px ;
}
.chap{
    width: 500px;
}
.chapters{
    color: aqua;
}
.delete{
    background-color: black;
    height: 30px;
    color: black;
    border: 2px solid white;
}
</style>
    <table align="center" class='main' bgcolor='black'>
        <tr>
            <th>
                <table align='center'>
                    <tr>
                        <th><img src="../data/truyen/<?=$truyen?>/chinh.jpg"></th>
                    </tr>
                </table>
            </th>
        </tr>
        <tr>
            <th class='tentruyen'>
                ------------------------------------------<br>
                <?=$tentruyen?>
                <br>------------------------------------------</th>
        </tr>
        <tr>
            <th class='motatruyen'>
                <hr>
            --- Mô Tả ---<br><?=$mota?><br>
<?=($_SESSION['tk'] == $nguoidang)?"<button onclick='capnhapmota();'>Cập nhập</button>":"" ?>
           <hr></th>
        </tr>
        <tr>
            <td class="gom">Thể Loại: 
<?php
    $cactheloai = mysqli_query($ketnoi,"SELECT TenTheLoai FROM thuoctheloai,theloai WHERE theloai.MaTheLoai = thuoctheloai.MaTheLoai AND MaTruyen = '$truyen'");
    $theloai = "";  
    while($row2 = mysqli_fetch_array($cactheloai)){
        if($theloai == ""){
            $theloai = $row2['TenTheLoai'];
        }else{
            $theloai = $theloai." - ".$row2['TenTheLoai'];
        }
    }
    echo "$theloai";
?>
        </tr>
        <tr>
            <td class="gom">Tác Giả: <?=$tacgia?>
            <?=($_SESSION['tk'] == $nguoidang)?"<button onclick='capnhaptacgia();'>Cập nhập</button>":"" ?></td>
        </tr>
        <tr>
            <td class="gom">Đăng Bởi: <?=$nguoidang?></td>
        </tr>
        <tr>
            <td class="gom">Số lượng lượt xem: <?=$luotxem?></td>
        </tr>
        <tr><td><hr></td></tr>
        <tr>
            <th>
                <table class='hienthichuong' align="center">
                    <tr class='chuong'>
                        <th >
                            Số Lượng chap: <?=$sochap?><br>
<?php
    for($i = 0; $i < $sochap;$i++){
        echo "<hr class='chap'><a class='chapters' href='doc.php?truyen=$truyen&chap=".($i+1)."'>Chapter: ".($i+1)."<tab>".$ngaydang[$i]."</tab></a>";
    }
?>
<?php
if($_SESSION['tk'] == $nguoidang){
    $chap = $sochap+1;
    echo "<hr>
    Up chap $chap:
    <form method='post' enctype='multipart/form-data'> 
    <input type='file' name='chapters[]' accept='image/jpeg' multiple>
    <input type='submit' value='UP!!!'>
    </form>";
}
?>

                        </th>   
                    </tr>
                    <tr>
                        <td align="right">
<?php
if($_SESSION['tk'] == $nguoidang){
echo "<form method='post'>
<input type='checkbox' id='xoa'>
<input type='submit' name='xoa' id='delete' value='XÓA TRUYỆN' class='delete' disabled>
</form>
<script>
document.getElementById('xoa').addEventListener('click',xoatruyen);
function xoatruyen(){
    if(document.getElementById('xoa').checked){
        document.getElementById('delete').style.color = 'aqua';
        document.getElementById('delete').disabled = false;
    }else{
        document.getElementById('delete').style.color = 'black';
        document.getElementById('delete').disabled = true;
    }   
}
</script>";
}
?>

                        </td>
                    </tr>
                </table>
            </th>
        </tr>
    </table>
</body>
</html>