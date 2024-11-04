function nombreVacio() {
    if (document.getElementById("nombre").value == "") {
        document.getElementById("errorNombre").style.display = "block";
    } else {
        document.getElementById("errorNombre").style.display = "none";
    }
}

function pwdVacio() {
    if (document.getElementById("pwd").value == "") {
        document.getElementById("errorPwd").style.display = "block";
    } else {
        document.getElementById("errorPwd").style.display = "none";
    }

}