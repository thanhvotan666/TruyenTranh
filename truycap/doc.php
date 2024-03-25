<?php
session_start();
if(!isset($_SESSION['tk'])){
    $_SESSION['tk'] ="";
}


    $ketnoi = mysqli_connect("localhost","root","","quanlytruyenonline");
    mysqli_set_charset($ketnoi,"utf8");
if(isset($_GET['truyen'],$_GET['chap'])){
    $truyen = $_GET['truyen'];
    $chap = $_GET['chap'];
}else{
    $path = str_replace("/truycap/doc.php","",$_SERVER['PHP_SELF']);
    header("Location: $path/trangchu.php");
}
if(isset($_GET['out'])){
    $_SESSION['tk'] = '';
    header("Location: ".$_SERVER['PHP_SELF']."?truyen=$truyen&chap=$chap");
}
mysqli_query($ketnoi,"UPDATE truyen SET LuotXem = LuotXem +1 WHERE MaTruyen = '$truyen'");
$tentruyen ="";
$bangtruyen =mysqli_query($ketnoi,"SELECT * FROM truyen WHERE MaTruyen = '$truyen'");
while($row=mysqli_fetch_array($bangtruyen)){
    $tentruyen = $row['TenTruyen'];
}
$bangchap = mysqli_query($ketnoi,"SELECT * FROM chapters WHERE MaTruyen = '$truyen' ORDER BY Chapter DESC LIMIT 1");
$chapmax = "";
while($row3 = mysqli_fetch_array($bangchap)){
    $chapmax = $row3['Chapter'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="../icon/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="tieude.css">
    <title>Chương <?=$_GET['chap']?></title>
</head>
<script>
    var chap = <?=$chap?>;
    var chapmax = <?=$chapmax?>;
    if(chap == 1){
        document.getElementById('giam1').href="#";
    }
    if(chap == chapmax){
        document.getElementById('tang1').href="#";
    }
    if(chap == 1){
        document.getElementById('giam2').href="#";
    }
    if(chap == chapmax){
        document.getElementById('tang2').href="#";
    }
    function chonchapter() {
        var string = prompt("Nhập chap từ 1 đến <?=$chapmax?>:");
        if(string != null && Number(string) && Number(string) <= <?=$chapmax?>){
            document.location = "<?=$_SERVER['PHP_SELF']."?truyen=$truyen&chap="?>"+string;
        }else{
            alert("Không hợp lệ!!!");
        }
    }
</script>

<body>
<?php
if(isset($_POST['binhluan'])){
    $binhluan =  $_POST['binhluan'];
    if($_SESSION['tk'] == ""){
        echo "<script>
    alert('Bình luận thất bại! Bạn phải đăng nhập để được bình luận!!!');
    document.location = document.location;
    </script>";
    }else if($binhluan == ""){
        echo "<script>
        alert('Bình luận thất bại! Bình luận không được để trống!!!');
        document.location = document.location;
        </script>";
    }
    else{
        $bangmachapter = mysqli_query($ketnoi,"SELECT MaChapter FROM chapters WHERE MaTruyen = '$truyen' AND Chapter = '$chap'");
        $machapter = "";
        while($row= mysqli_fetch_array($bangmachapter)){
            $machapter = $row['MaChapter'];
        }
        mysqli_query($ketnoi,"INSERT INTO binhluan(MaChapter,TenDangNhap,NoiDung) values
        ($machapter,'".$_SESSION['tk']."','$binhluan')");
        echo "<script>
        document.location = document.location;
        </script>";
    }
}
?>
    <div class='header'>
        <div class='h1'><a href="../trangchu.php"><img src="../icon/icon.png" class='icon'></a></div>
        <a class='h2' href="../trangchu.php">Truyện Tranh ONLINE</a>
        <?php
            if($_SESSION['tk'] == ""){
                echo "
                <div class='h3'><a href='dangnhap.php?doc=&truyen=$truyen&chap=$chap' class='dn'>Login</a></div>
                <div class='h4'><a href='dangky.php?doc=&truyen=$truyen&chap=$chap' class='dn'>Đăng ký</a></div>";
            }else{
                echo "
                <div class='h3'><h2>".$_SESSION['tk']."</h2></div>
                <div class='h4'><a href='doc.php?out=&truyen=$truyen&chap=$chap' class='dn'>Đăng xuất</a></div>";
            }
        ?>
    </div>
    <table align="center">
        <tr>
            <th>
                <hr><a href="truyen.php?truyen=<?=$truyen?>"><h2 class="tentruyen"><?=$tentruyen?></h2></a><hr>
            </th>
        </tr>
<style>
.tentruyen{
    color: chartreuse;
}
.chuyentrang{
    display: flex;
    justify-content: center;
    align-items: center;
}
.tanggiam{
    flex: 5;
    margin-left: 100px;
    margin-right: 100px;
    border-radius: 50px;
    padding: 25px;
    background-color: chartreuse;
    box-shadow: 5px 10px 1px green;
    font-size: 30px;
    color: white;
}
.menu{
    width: 50px;
    height: 50px;
}
.textbinhluan{
    width: 500px;
    height: 75px;
    margin-left: 50px ;
    margin-right: 50px;
    border-radius: 50px;
}
.textbinhluansau{
    color: black;
    width: 500px;
    margin-left: 50px;
    margin-right: 50px;
    padding: 50px;
    border-radius: 50px;
    background-color: wheat;
}
.nutbinhluan{
    width: 100px;
    height: 50px;
    border-radius: 20px;    
}
.thongtinbinhluan{
    width: 75px;
    padding-left: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cacbinhluan{
    display: grid;
    grid-template-columns: auto auto;
}
</style>

        <tr>
            <th class="chuyentrang">
                <a href="doc.php?truyen=<?=$truyen?>&chap=<?=$chap-1?>" class='tanggiam' id='giam1'><<<</a>
                <img src="../icon/menu.png" alt="<?=$tentruyen?>" class="menu" onclick="chonchapter();">
                <a href="doc.php?truyen=<?=$truyen?>&chap=<?=$chap+1?>" class='tanggiam' id='tang1'>>>></a> 
            </th>
        </tr>
        <tr >
            <th><br><br>
<?php
for($i = 1;file_exists("../data/truyen/$truyen/$chap/$i.jpg");$i++){
    echo "<img src='../data/truyen/$truyen/$chap/$i.jpg'><br>";
}
?><br>
            </th>
            
        </tr>
        <tr>
            <th class="chuyentrang">
                <a href="doc.php?truyen=<?=$truyen?>&chap=<?=$chap-1?>" class='tanggiam' id='giam2'><<<</a>
                <img src="../icon/menu.png" alt="<?=$tentruyen?>" class="menu" onclick="chonchapter();">
                <a href="doc.php?truyen=<?=$truyen?>&chap=<?=$chap+1?>" class='tanggiam' id='tang2'>>>></a> 
            </th>
        </tr>
        <tr>
            <th>
                <br><br>
                <form method="post">
                    <input type="text" name="binhluan" class='textbinhluan'>
                    <input type="submit" value="Bình Luận" class='nutbinhluan' id='binhluan'>
                </form>
                <hr><br>
            </th>
        </tr>
<?php
    $bangbinhluan = mysqli_query($ketnoi,"SELECT MaBinhLuan,TenDangNhap,Chapter,NoiDung FROM binhluan B,chapters C
                                WHERE B.MaChapter = C.MaChapter AND MaTruyen = '$truyen' ORDER BY MaBinhLuan DESC");
    while($row = mysqli_fetch_array($bangbinhluan)){
        $ten = $row['TenDangNhap'];
        $chapter = $row['Chapter'];
        $nd = $row['NoiDung'];
        echo "
        <tr>
            <th class='cacbinhluan'>
                <div class='thongtinbinhluan'>$ten<br>Chapter: $chapter</div>
                <div class='textbinhluansau'>$nd</div>
            </th>
        </tr>
        ";
    }
?>
    </table>
</body>
</html>


