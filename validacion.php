<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validacion</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>


<?php

$error = "";

function ValidaCampoVacio($campo) {
    return empty($campo); // true si está vacío, false si no lo está
}

include "./conexion.php";

// Verifica que el formulario fue enviado
if (!filter_has_var(INPUT_POST, 'enviar')) {
    header('Location: ./index.php');
    exit();
}

$errores = "";
$username = mysqli_real_escape_string($conn, htmlspecialchars($_POST['nombre']));
$password = mysqli_real_escape_string($conn, htmlspecialchars($_POST['pwd']));

// Validación de campos vacíos
if (ValidaCampoVacio($username)) {
    $errores .= (empty($errores) ? "?" : "&") . "usernameVacio=true";
}
if (ValidaCampoVacio($password)) {
    $errores .= (empty($errores) ? "?" : "&") . "passwordVacio=true";
}

// Si hay errores de campos vacíos, redirige de inmediato
if ($errores != "") {
    header("Location: ./index.php" . $errores);
    exit();
}

// Consulta base de datos para comprobar si el usuario existe
$sql = "SELECT * FROM tbl_usuarios WHERE nombre = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $id_usuario, $nombre, $apellidos, $email, $hashedPassword, $estado);
        mysqli_stmt_fetch($stmt);

        // Verifica la contraseña
        if (password_verify($password, $hashedPassword)) {
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
            // Redirige a Wazaa.php si las credenciales son correctas
            header("Location: ./amigos.php");
            exit(); // Asegúrate de llamar a exit() aquí
        } else {
            // Si la contraseña es incorrecta
            $errores .= (empty($errores) ? "?" : "&") . "passwordMal=true";
        }
    } else {
        // Si el usuario no existe
        $errores .= (empty($errores) ? "?" : "&") . "usernameMal=true";

        // También añade passwordMal=true para indicar que ambos son incorrectos
        if (!ValidaCampoVacio($password)) {
            $errores .= "&passwordMal=true";
        }
    }

    mysqli_stmt_close($stmt);
} else {
    // Si la preparación de la consulta falla
    $errores .= (empty($errores) ? "?" : "&") . "dbError=true";
}

// Si hubo errores, redirige de nuevo al index con los errores
if ($errores != "") {
    $datosrecibidos = array(
        'username' => $username,
        'password' => $password,
    );
    $datosDevueltos = http_build_query($datosrecibidos);
    header("Location: ./index.php" . $errores . "&" . $datosDevueltos);
    exit();
}
?>


</body>
</html>