<?php

    include_once("./conexion.php");

    session_start();

    if (!isset($_SESSION['loggedin']) && !isset($_SESSION['id_usuario'])) {
        header('Location: ' . './login/login.php');
        exit();
    } elseif (!isset($_GET['receptor'])) {
        header('Location: ' . './index.php');
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
    <link rel="stylesheet" href="styles.css">
</head>
<body class="cuerpo">
    <div class="caja-contenedor">
        <header class="encabezado">
            <h1>Chat</h1>
        </header>
        <div class="contenedor-mensajes">
            <?php
                // Mostrar mensajes (SELECCIONAMOS todo de la tabla mensajes, los nombres del usuario emisor y receptor
                // (mediante las FK de la tabla tbl_usuarios), UNIMOS las FK, le decimos que haga esto
                // CUANDO el emisor sea igual a la ID del usuario de nuestra sesión Y el receptor sea igual a la ID del amigo que hemos elejido para chatear, y lo
                // ORDENAMOS POR la fecha, de más antiguo a más nuevo)
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

                    // Recorre cada mensaje y muestra el nombre del emisor o receptor según corresponda
                    while (mysqli_stmt_fetch($stmtMostrar)) {
                        // Mostrar el mensaje del emisor
                        $claseMensaje = ($usuarioEnvia == $emisor) ? 'mensaje-mio' : 'mensaje-suyo';
                        echo "<p class='$claseMensaje'><strong>" . htmlspecialchars($nombreEmisor) . ":</strong> " . htmlspecialchars($mensaje_chat) . "</p>";
                    }

                } else {
                    echo "<p>Todavía no hay mensajes.</p>";
                }
            ?>
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
                        // Redireccionar después de procesar el formulario para evitar reenvío
                        header("Location: " . $_SERVER['PHP_SELF']. "?receptor=" . urlencode($receptor));
                        exit();
                    }
                }
            ?>
        </div>
    </div>
    <div class="caja-mensaje">
        <form method="POST">
            <input type="text" name="mensaje" class="input-mensaje" id="mensaje">
            <input type="submit" class="boton-enviar" name="Enviar" value="Enviar">           
        </form>
        <?php
            if (isset($error) && $error == 1) {
                echo '<p style="color: red;">El mensaje no puede estar vacío.</p>';
            }
        ?>
    </div>
</body>
</html>
<?php
    }
?>