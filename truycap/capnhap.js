function capnhapmota() {
    var string = prompt("Mời bạn nhập mô tả mới: ");
    if(string != null && string != ""){
            document.location = document.location + "&mota="+string;
    }
}
function capnhaptacgia() {
    var string = prompt("Mời bạn nhập tên tác giả mới: ");
    if(string != null && string != ""){
        if(string.length >= 50){
            alert("Tên Tác giả không được nhiều hơn hoặc bằng 50 ký tự!!!");
        }else{
            document.location = document.location + "&tacgia="+string;
        }
    }
}