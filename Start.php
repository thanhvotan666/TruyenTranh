<?php
session_start();
$_SESSION['tk']= '';
//xoa
$created = false;
try {
    $ketnoi = mysqli_connect('localhost','root','','quanlytruyenonline') or die("Lỗi kết nối");
    mysqli_close($ketnoi);
    $created =true;
}
catch(\Throwable $th){
    echo "Lỗi kết nối";
}
try{
    //tao
$ketnoi = mysqli_connect('localhost','root','') or die("Lỗi kết nối");
mysqli_set_charset($ketnoi,'utf8');
if($created){
    mysqli_query($ketnoi,"DROP DATABASE quanlytruyenonline");
}
if(mysqli_query($ketnoi,"CREATE DATABASE quanlytruyenonline")){
    mysqli_close($ketnoi);
    $ketnoi = mysqli_connect('localhost','root','','quanlytruyenonline')or die("Lỗi kết nối");
    mysqli_set_charset($ketnoi,'utf8mb4');

    $tabletaikhoan = "CREATE TABLE taikhoan(
        TenDangNhap varchar(20) not null,
        MatKhau varchar(30) not null,
        primary key (TenDangNhap)
    );";

    $tabletheloai = "CREATE TABLE theloai(
        MaTheLoai int(5) AUTO_INCREMENT,
        TenTheLoai varchar(20) not null,
        MoTa text,
        primary key (MaTheLoai)
    );";
    $tabletruyen = "CREATE TABLE truyen(
        MaTruyen varchar(15) not null,
        TenTruyen varchar(50) not null,
        MoTaTruyen text default 'Đang cập nhập',
        TacGia varchar(50) default 'Đang cập nhập',
        TenDangNhap varchar(20),
        LuotXem int default 0,
        primary key (MaTruyen),
        foreign key (TenDangnhap) references taikhoan(TenDangnhap)
    );
    ";
    $tablethuoctheloai = "CREATE TABLE thuoctheloai(
        MaTruyen varchar(15) not null,
        MaTheLoai int(5) not null,
        primary key (MaTruyen,MaTheLoai),
        foreign key (MaTruyen) references truyen(MaTruyen),
        foreign key (MaTheLoai) references theloai(MaTheLoai)
    );
    ";
    $tablechapters = "CREATE TABLE chapters(
        MaChapter int(5) AUTO_INCREMENT,
        MaTruyen varchar(15) not null,
        Chapter int(5) not null,
        NgayDang datetime not null,
        primary key (MaChapter),
        foreign key (MaTruyen) references truyen(MaTruyen)
    );
    ";
    $tablebinhluan = "CREATE TABLE binhluan(
        MaBinhLuan int(5) AUTO_INCREMENT,
        TenDangNhap varchar(20) not null,
        MaChapter int(5) not null,
        NoiDung text not null,
        primary key(MaBinhLuan),
        foreign key (TenDangNhap) references taikhoan(TenDangNhap),
        foreign key (MaChapter) references chapters(MaChapter)
    );
    ";
    mysqli_query($ketnoi,$tabletaikhoan);
    mysqli_query($ketnoi,$tabletheloai);
    mysqli_query($ketnoi,$tabletruyen);
    mysqli_query($ketnoi,$tablethuoctheloai);
    mysqli_query($ketnoi,$tablechapters);
    mysqli_query($ketnoi,$tablebinhluan);
    //insert thể loại
    $inserttheloai = "INSERT INTO theloai(TenTheLoai,MoTa) values
    ('Hành Động','Thể loại này thường có nội dung về đánh nhau, bạo lực, hỗn loạn, với diễn biến nhanh'),
    ('Phiêu Lưu','Thể loại phiêu lưu, mạo hiểm, thường là hành trình của các nhân vật'),
    ('Chuyển Sinh','Thể loại này là những câu chuyện về người ở một thế giới này xuyên đến một thế giới khác, có thể là thế giới mang phong cách trung cổ với kiếm sĩ và ma thuật, hay thế giới trong game, hoặc có thể là bạn chết ở nơi này và được chuyển sinh đến nơi khác'),
    ('Cooking','Thể loại có nội dung về nấu ăn, ẩm thực'),
    ('Cổ Đại','Truyện có nội dung xảy ra ở thời cổ đại phong kiến'),
    ('Giả Tưởng',''),
    ('Hài Hước',''),
    ('Truyện Màu','')
    ";
    mysqli_query($ketnoi,$inserttheloai);
    $insertadmin = "INSERT INTO taikhoan values
    ('admin','123456789'),
    ('thanh','thanh123'),
    ('phat','phat456'),
    ('neko','neko789'),
    ('alibaba','123')
    ";
    mysqli_query($ketnoi,$insertadmin);
    $inserttruyen = "INSERT INTO truyen(MaTruyen,TenTruyen,TenDangNhap,LuotXem) values
    ('AHTKLLR','Anh Hùng Ta Không Làm Lâu Rồi','admin',2),
    ('ĐPĐCN','Đại Phụng Đả Canh Nhân','admin',5),
    ('PLĐ','Phi Lôi Đạo','admin',1),
    ('LKKD','Lạn Kha Kì Duyên','admin',0),
    ('SHCTQCTR','Sư Huynh Của Ta Quá Cẩn Thận Rồi','admin',9)";
    mysqli_query($ketnoi,$inserttruyen);
    $inserttheloaitruyen = "INSERT INTO thuoctheloai values
    ('AHTKLLR',1),('AHTKLLR',6),('AHTKLLR',7),('AHTKLLR',8),
    ('ĐPĐCN',1),('ĐPĐCN',3),('ĐPĐCN',5),('ĐPĐCN',7),('ĐPĐCN',8),
    ('PLĐ',1),('PLĐ',2),('PLĐ',5),('PLĐ',6),('PLĐ',7),('PLĐ',8),
    ('LKKD',3),('LKKD',5),('LKKD',6),('LKKD',8),
    ('SHCTQCTR',1),('SHCTQCTR',3),('SHCTQCTR',5),('SHCTQCTR',6),('SHCTQCTR',8)
    ";
    mysqli_query($ketnoi,$inserttheloaitruyen);
    $insertchapters = "INSERT INTO chapters(MaTruyen,Chapter,NgayDang) values
    ('AHTKLLR',1,'2023-4-23 2:25:00'),
    ('ĐPĐCN',1,'2023-4-23 2:25:01'),
    ('PLĐ',1,'2023-4-23 2:25:02'),
    ('LKKD',1,'2023-4-23 2:25:03'),
    ('SHCTQCTR',1,'2023-4-23 2:25:04'),
    ('SHCTQCTR',2,'2023-4-23 2:25:05'),
    ('ĐPĐCN',2,'2023-4-23 2:25:06'),
    ('ĐPĐCN',3,'2023-4-23 2:25:07'),
    ('ĐPĐCN',4,'2023-4-23 2:25:08'),
    ('ĐPĐCN',5,'2023-4-23 2:25:09')
    ";
    mysqli_query($ketnoi,$insertchapters);
    $insertbinhluan = "INSERT INTO binhluan(TenDangNhap,MaChapter,NoiDung) values
    ('thanh',2,'Xin chèo mụi người :3'),
    ('phat',2,'Truyện hay hông ae? :v'),
    ('admin',1,'Truyện Đang Cập Nhập'),
    ('neko',7,'Đang là chap 2 à ?'),
    ('neko',9,'Chắc chắn là chap 4 nhờ :V')";
    mysqli_query($ketnoi,$insertbinhluan);
    mysqli_close($ketnoi);
    $path = str_replace("Start.php","",$_SERVER['PHP_SELF']);
    header("Location: $path/trangchu.php");
}else{
    mysqli_close($ketnoi);
    echo "Không thể tạo database";
}
} catch (\Throwable $th) {
    echo "TẠO DATABASE THẤT BẠI!!!";
}

?>
