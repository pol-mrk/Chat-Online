<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>

<div class="form-container">

<?php

if (!filter_has_var(INPUT_POST, 'enviar2')) {
    header('Location: ./index.php');
    exit();
}

include "./conexion.php"; 

// Solo ejecuta si el método es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Datos del usuario
    $nombre = mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['nombreRegistrar'])));
    $apellidos = mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['apellidoRegistrar'])));
    $email = mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['emailRegistrar'])));
    
    $contrasena = $_POST['registrarContrasena']; 
    $repetirContrasena = $_POST['registrarRepetirContrasena']; 


// Validaciones
$errores = "";

// Validación del nombre
if (empty($nombre)) {
    $errores .= (empty($errores) ? "?" : "&") . "nombreVacio=true&nombreRegistrar=" . $nombre;
} elseif (strlen($nombre) < 3) {
    $errores .= (empty($errores) ? "?" : "&") . "nombreCorto=true&nombreRegistrar=" . $nombre;
} elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $nombre)) {
    $errores .= (empty($errores) ? "?" : "&") . "nombreInvalido=true&nombreRegistrar=" . $nombre;
}

// Validación del apellido
if (empty($apellidos)) {
    $errores .= (empty($errores) ? "?" : "&") . "apellidoVacio=true&apellidoRegistrar=" . $apellidos;
} else {
    
    // Separar los apellidos y comprobar cada uno
    $listaApellidos = explode(" ", trim($apellidos));
    if (count($listaApellidos) < 2) {
        $errores .= (empty($errores) ? "?" : "&") . "apellidoSeparado=true&apellidoRegistrar=" . $apellidos;
    } else {
        foreach ($listaApellidos as $apellido) {
            if (strlen($apellido) < 3 || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $apellido)) {
                $errores .= (empty($errores) ? "?" : "&") . "apellidoInvalido=true&apellidoRegistrar=" . $apellidos;
                break;
            }
        }
    }
}



// Validación del email
if (empty($email)) {
    $errores .= (empty($errores) ? "?" : "&") . "emailVacio=true&emailRegistrar=" . $email;
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores .= (empty($errores) ? "?" : "&") . "emailInvalido=true&emailRegistrar=" . $email;
}

// Validación de la contraseña
if (empty($contrasena)) {
    $errores .= (empty($errores) ? "?" : "&") . "passwordVacio=true";
} elseif (!preg_match('/[A-Z]/', $contrasena) || !preg_match('/[a-z]/', $contrasena) || !preg_match('/[0-9]/', $contrasena)) {
    $errores .= (empty($errores) ? "?" : "&") . "passwordInvalida=true";
}

if($contrasena != $repetirContrasena) {
    $errores .= (empty($errores) ? "?" : "&") . "repetirPassword=true";
} 

// Si hay errores, redirige de nuevo al formulario con los errores y los datos del formulario
if ($errores != "") {
    header("Location: ./registrar.php" . $errores . "&nombreRegistrar=" . urlencode($nombre) . "&apellidoRegistrar=" . urlencode($apellidos) . "&emailRegistrar=" . urlencode($email));
    exit();
}


    // Si no hay errores, encripta la contraseña usando bcrypt
    $hashedPassword = password_hash($contrasena, PASSWORD_BCRYPT);

    // Prepara la consulta SQL
    $sql = "INSERT INTO chat_online.tbl_usuarios (nombre, apellidos, email, contrasena, estado) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Vincula los parámetros
        mysqli_stmt_bind_param($stmt, "sssss", $nombre, $apellidos, $email, $hashedPassword, $estado);

        // Ejecuta la consulta
        if (mysqli_stmt_execute($stmt)) {
            echo "<div class='success-message'>Usuario insertado exitosamente con contraseña encriptada.</div>";
        } else {
            echo "<div class='error-message'>Error al insertar el usuario: " . mysqli_stmt_error($stmt) . "</div>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<div class='error-message'>Error en la preparación de la consulta: " . mysqli_error($conn) . "</div>";
    }

    mysqli_close($conn);
}
?>

<br>
<div class="volver-container">
    <a href="./index.php" class="volver-btn">Volver</a>
</div>
</div>

</body>
</html>
