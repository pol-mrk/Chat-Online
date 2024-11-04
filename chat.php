<?php

include_once("./conexion.php");

session_start();

if (!isset($_SESSION['loggedin']) && !isset($_SESSION['id_usuario'])) {
    header('Location: ' . './index.php');
    exit();
} elseif (!isset($_GET['receptor'])) {
    header('Location: ' . './amigos.php');
    exit();
} else {
    $emisor = mysqli_real_escape_string($conn, htmlspecialchars($_SESSION['id_usuario']));
    $receptor = isset($_GET['receptor']) ? mysqli_real_escape_string($conn, htmlspecialchars($_GET['receptor'])) : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body class="cuerpo bodyBuscar">
        <header class="encabezado" style="justify-content:space-between !important">
    <h1 style="padding-left:910px; font-size: 40px">Chat</h1>
            <div>

        <a class="iconos" href="amigos.php" style="color: white;">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="black" class="bi bi-people-fill" viewBox="0 0 16 16">
                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                </svg>
            </a>
            <a class="iconos" href="solicitudes.php" style="color: white;">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="black" class="bi bi-person-check-fill" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L12.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                    <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                </svg>
            </a>
    </div>
        </header>

        <div class="centrar">
        <div class="caja-contenedor">

        <div class="contenedor-mensajes">
            <?php
                $sqlMostrar = "SELECT emisor, receptor, mensaje_chat, fecha_chat, usuario_emisor.nombre AS nombre_emisor, usuario_receptor.nombre AS nombre_receptor FROM tbl_mensajes
                INNER JOIN tbl_usuarios AS usuario_emisor ON usuario_emisor.id_usuario = tbl_mensajes.emisor
                INNER JOIN tbl_usuarios AS usuario_receptor ON usuario_receptor.id_usuario = tbl_mensajes.receptor
                WHERE (tbl_mensajes.emisor = ? AND tbl_mensajes.receptor = ?) OR (tbl_mensajes.emisor = ? AND tbl_mensajes.receptor = ?)
                ORDER BY fecha_chat ASC";
                
                $stmtMostrar = mysqli_prepare($conn, $sqlMostrar);
                mysqli_stmt_bind_param($stmtMostrar, "iiii", $emisor, $receptor, $receptor, $emisor);
                mysqli_stmt_execute($stmtMostrar);
                mysqli_stmt_store_result($stmtMostrar);

                if (mysqli_stmt_num_rows($stmtMostrar) > 0) {
                    mysqli_stmt_bind_result($stmtMostrar, $usuarioEnvia, $usuarioRecibe, $mensaje_chat, $fechaMensaje, $nombreEmisor, $nombreReceptor);

                    while (mysqli_stmt_fetch($stmtMostrar)) {
                        $claseMensaje = ($usuarioEnvia == $emisor) ? 'mensaje-mio' : 'mensaje-suyo';
                        echo "<p class='$claseMensaje'><strong>" . htmlspecialchars($nombreEmisor) . ":</strong> " . htmlspecialchars($mensaje_chat) . "</p>";
                    }
                } else {
                    echo "<p>Todavía no hay mensajes.</p>";
                }
            ?>
        </div>
        <div class="caja-mensaje">
            <form method="POST" id="inputChat">
                <input type="text" name="mensaje" class="input-mensaje" id="mensaje">
                <input type="submit" class="boton-enviar" name="Enviar" value="Enviar">           
            </form>
            <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $error = 0;
                    $mensaje = mysqli_real_escape_string($conn, htmlspecialchars($_POST["mensaje"]));

                    if (empty($mensaje)) {
                        $error = 1;
                    } else {
                        $error = 0;
                        $sqlEnviar = "INSERT INTO tbl_mensajes (emisor, receptor, mensaje_chat) VALUES (?, ?, ?);";
                        $stmtEnviar = mysqli_prepare($conn, $sqlEnviar);
                        mysqli_stmt_bind_param($stmtEnviar, "iis", $emisor, $receptor, $mensaje);
                        mysqli_stmt_execute($stmtEnviar);
                        mysqli_stmt_close($stmtEnviar);
                        header("Location: " . $_SERVER['PHP_SELF']. "?receptor=" . urlencode($receptor));
                        exit();
                    }
                }
            ?>
        </div>
    </div>
            </div>
</body>
</html>
<?php
    }
?>
