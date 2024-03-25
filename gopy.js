document.getElementById('gopy').addEventListener('click',gopy);
    function gopy(){
        var string = prompt("GÓP Ý: ");
        if(string != null && string != ""){
                document.location = document.location + "?gopy="+string;
        }
    }