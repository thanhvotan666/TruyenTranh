<?php
session_start();
if(isset($_GET['out'])){
    $_SESSION['tk'] = '';
    header("Location: ".$_SERVER['PHP_SELF']);
}
if(isset($_GET['gopy'])){
    $tk = $_SESSION['tk'];
    $gopy = $_GET['gopy'];
    $j = 0;
    while(file_exists("data/gopy/$tk$j.txt")){
        $j++;
    }
    $file = fopen("data/gopy/$tk$j.txt","w");
    fwrite($file,$gopy);
    fclose($file);
    header("Location: ".$_SERVER['PHP_SELF']);
}
    $ketnoi = mysqli_connect("localhost","root","","quanlytruyenonline");
    mysqli_set_charset($ketnoi,"utf8");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="icon/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="trangchu.css">
    <title>Trang chủ</title>
</head>
<style>
.upload{
    border-radius: 20px;
    padding: 20px;
    background-color: chartreuse;
    box-shadow: 0px 0px 50px green;
}
.gopy{
    border-radius: 20px;
    padding: 10px;
    font-weight: bolder;
    background-color:honeydew;
    box-shadow: 0px 0px 50px red;
}
</style>
<body>
    <div class='header'>
        <div class='h1'><a href="trangchu.php"><img src="icon/icon.png" class='icon'></a></div>
        <a class='h2' href="trangchu.php">Truyện Tranh ONLINE</a>
        <?php
            if($_SESSION['tk'] == ""){
                echo "
                <div class='h3'><a href='truycap/dangnhap.php' class='dn'>Login</a></div>
                <div class='h4'><a href='truycap/dangky.php' class='dn'>Đăng ký</a></div>";
            }else{
                echo "
                <div class='h3'><h2>".$_SESSION['tk']."</h2></div>
                <div class='h4'><a href='trangchu.php?out=' class='dn'>Đăng xuất</a></div>";
            }
        ?>
    </div>

    <table align="center">
        <tr>
            <th><form method="get">
                <div class="theloai">
                    <div class="tags">
                        <?php
                            $ketquatheloai = mysqli_query($ketnoi,"SELECT TenTheLoai FROM theloai");
                            while($row = mysqli_fetch_array($ketquatheloai)){
                                echo "<input type='submit' name='theloai' value='".$row['TenTheLoai']."'>";
                            }
                        ?>
                    </div>
                </div>
            </form>
            </th>
        </tr>
        <?php
        if(isset($_GET['theloai'])){
            $tentheloai = $_GET['theloai'];
            $motatheloai = mysqli_query($ketnoi,"SELECT MoTa FROM theloai WHERE TenTheLoai = '$tentheloai'");
            while ($row = mysqli_fetch_array($motatheloai)) {
                echo "<tr>
                        <th class='motatheloai'>
                        $tentheloai : ".$row['MoTa']."
                        </th>
                    </tr>";
            }
        }
        ?>
        <tr>
            <td class="main">
                <table class="trai">
                    <tr>
                        <th>
                            <form method="get">
                                <br>
                                <input type="search" name="tim" placeholder="Tìm Truyện">
                                <input type="submit" value="Tìm">
                                <br>
                                <br>
                            </form>
<?=$_SESSION['tk']==""?"":"<br><br>
                            <a href='truycap/addtruyen.php' class='upload'>upload truyện !!!</a>
                            <br><br><br>
                            <button class='gopy' id='gopy'>GÓP Ý</button><br><br>
                            <script src='gopy.js'></script>"?>

                        </th>
                    </tr>
<?php
//Hiện
$bangmatruyen = mysqli_query($ketnoi,"SELECT MaTruyen,NgayDang FROM chapters ORDER BY NgayDang DESC");
$arrmatruyen = [];
function cotrongarr($mang,$s){
    foreach($mang as $j){
        if($j === $s){
            return true;
        }
    }
    return false;
}
while($row = mysqli_fetch_array($bangmatruyen)){
    if(!cotrongarr($arrmatruyen,$row['MaTruyen'])){
        $arrmatruyen[] = $row['MaTruyen'];
    }
}
foreach($arrmatruyen as $ma){
    $listtruyen = mysqli_query($ketnoi,"SELECT MaTruyen,TenTruyen,TenDangNhap,LuotXem FROM truyen WHERE MaTruyen = '$ma'");
    
    while($row = mysqli_fetch_array($listtruyen)){
        $matruyen = $row['MaTruyen'];
//Tìm
        if(isset($_GET['tim'])){
            $tim = strtolower($_GET['tim']);
            if (strlen(strstr(strtolower($row['TenTruyen']),$tim)) == 0) {
                    continue;
                }
        }else if(isset($_GET['theloai'])){
            $tl = $_GET['theloai'];
            $bangkiemtratheloai = mysqli_query($ketnoi,"SELECT MaTruyen FROM thuoctheloai,theloai WHERE MaTruyen = '$matruyen' 
            AND thuoctheloai.MaTheLoai = theloai.MaTheLoai AND TenTheLoai = '$tl'");
            if(mysqli_num_rows($bangkiemtratheloai) == 0){
                continue;
            }
        }
        $cactheloai = mysqli_query($ketnoi,"SELECT TenTheLoai FROM thuoctheloai,theloai WHERE theloai.MaTheLoai = thuoctheloai.MaTheLoai AND MaTruyen = '$matruyen'");
        $theloai = "";  
        while($row2 = mysqli_fetch_array($cactheloai)){
            if($theloai == ""){
                $theloai = $row2['TenTheLoai'];
            }else{
                $theloai = $theloai." - ".$row2['TenTheLoai'];
            }
        }
        $ngaydang = "";
        $chapmax = "";
        $chapterup = mysqli_query($ketnoi,"SELECT * FROM chapters WHERE MaTruyen = '$matruyen' ORDER BY Chapter DESC LIMIT 1");
        while($row3 = mysqli_fetch_array($chapterup)){
            $ngaydang = $row3['NgayDang'];
            $chapmax = $row3['Chapter'];
        }
        echo "
                        <tr>
                            <td>
                                <table class='trai' bgcolor='black' >   
                                    <tr>
                                        <td><img src='data/truyen/$matruyen/chinh.jpg' class='anhchinh'></td>
                                        <td class='motatruyen'>
                                            <h2><a href='truycap/truyen.php?truyen=$matruyen'><xanhla>".$row['TenTruyen']."</xanhla></a></h2> <br>
                                            Thể loại: <aqua>$theloai</aqua><br>
                                            Chapter mới nhất: <a href='truycap/doc.php?truyen=$matruyen&chap=$chapmax'><xanhla>$chapmax</xanhla></a><br>
                                            Ngày đăng: <a href='truycap/doc.php?truyen=$matruyen&chap=$chapmax'><xanhla>$ngaydang</xanhla></a><br>
                                            Người đăng: <aqua>".$row['TenDangNhap']."</aqua><br>
                                            Số lượt xem: <aqua>".$row['LuotXem']."</aqua>
                                        </td>
                                    </tr>   
                                </table>
                            </td>
                        </tr>
        ";
    }
}
?>
                </table>
                
                <table class='phai' bgcolor='black'>
                    <tr>
                        <th colspan="2">TOP lượt xem nhiều nhất</th>
                    </tr>
<?php
    $bangtoptruyen = mysqli_query($ketnoi,"SELECT MaTruyen,TenTruyen,LuotXem FROM truyen ORDER BY LuotXem DESC LIMIT 5");
    $top = 1;
    while($row = mysqli_fetch_array($bangtoptruyen)){
        echo "
    <tr>
        <th colspan='2'><hr></th>
    </tr>
    <tr>
        <td ><h1 class='sotop'>$top</h1></td><td ><a href='truycap/truyen.php?truyen=".$row['MaTruyen']."' class='tentop'>".$row['TenTruyen']."</a><br>Số Lượt Xem: ".$row['LuotXem']."</td>
    </tr>
        ";
        $top++;
    }
?>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>