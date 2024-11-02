<?php
$nombre = $_POST["nombre"];
$apellidos = $_POST["apellidos"];
$email = $_POST["email"];
$contrasena = $_POST["contrasena"];
$hash = password_hash($contrasena, PASSWORD_BCRYPT);
$reptr_contrasena = $_POST["reptr_contrasena"];
include("../../conexion.php");        


if (password_verify($reptr_contrasena, $hash)) {
    $stmt_insert_user = mysqli_stmt_init($conn);
    $sql_insert_user = "INSERT INTO tbl_usuarios(nombre, apellidos, email, contrasena, estado) VALUES (?, ?, ?, ?, 'En línea')";
    mysqli_stmt_prepare($stmt_insert_user, $sql_insert_user);
    mysqli_stmt_bind_param($stmt_insert_user, "ssss", $nombre, $apellidos, $email, $hash);
    mysqli_stmt_execute($stmt_insert_user);
    header("location: ../../login/login.php");
}
?>
