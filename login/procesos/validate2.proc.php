<?php

$error="";
function validaCampoVacio($campo) {
    if(empty($campo)){
        $error= true; //Hay un error
    }else{
        $error= false; //No hay un error
    }
    return $error;
}


if (!filter_has_var(INPUT_POST, 'login')) {
    header('Location: '.'../login.php');
    exit();
} else {

$errores="";

$nombre = $_POST['nombre'];
$contrasena = $_POST['contrasena'];

include("../../conexion.php");

if (validaCampoVacio($nombre)){
    if (!$errores){
        $errores .="?usernameVacio=true";
    } else {
        $errores .="&usernameVacio=true";        
    }
}

if (validaCampoVacio($contrasena)){
    if (!$errores){
        $errores .="?contrasenaVacio=true";
     } else {
        $errores .="&contrasenaVacio=true";        
     }
}

/* La variable 'sql' hace una consulta donde: selecciona todo de la tabla 'tbl_usuarios' donde el usuario es igual a la variable '$user' y la contrasena igual a la variable '$contrasena' */ 
$sql = "SELECT * FROM tbl_usuarios WHERE nombre=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $nombre);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {

    mysqli_stmt_bind_result($stmt, $id_usuario, $nombre, $apellidos, $email, $contrasenadb, $estado);

    mysqli_stmt_fetch($stmt);

    /*Comprobamos que la contrasena puesta sea igual a la de la base de datos (el hash)*/
    if (password_verify($contrasena, $contrasenadb)) {
        /* Creamos la sesión */
        session_start();
        $_SESSION['loggedin'] = true;
        $id = $id_usuario;
        $estado = "En línea";
        $sql2 = "UPDATE tbl_usuarios SET estado=? WHERE nombre=?";
        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt2, "ss", $estado, $nombre);
        if (mysqli_stmt_execute($stmt2)) {
            $_SESSION['id_usuario'] = $id;
        }
        header("location: ../../index.php");
    } else {
        if ($contrasena != "") {
            if (!$errores){
                $errores .="?contrasenaMal=true";
            } else {
                $errores .="&contrasenaMal=true";        
            }
        }
            
    }    
}

else {
    if ($nombre != "") {
            if (!$errores){
                $errores .="?usernameMal=true";
            } else {
                $errores .="&usernameMal=true";        
            }
    }
        
}

if ($errores!=""){

    $datosRecibidos = array(
        'nombre' => $nombre,
        'contrasena' => $contrasena,
    );

    $datosDevueltos=http_build_query($datosRecibidos);
    header("Location: ../login.php". $errores. "&". $datosDevueltos);
    exit();
}else{
    echo"<form id='EnvioCheck' action='../../index.php' method='POST'>";
    echo"<input type='hidden' id='nombre' name='nombre' value='".$nombre."'>";
    echo"<input type='hidden' id='contrasena' name='contrasena' value='".$contrasena."'>";
    echo"</form>";
    echo "<script>document.getElementById('EnvioCheck').submit();</script>";
 }
}