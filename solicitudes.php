<?php

    include_once("./conexion.php");

    session_start();

    if (!isset($_SESSION['loggedin']) && !isset($_SESSION['id_usuario'])) {
        header('Location: ' . './login/login.php');
        exit();
    } else {
        $mi_usuario = mysqli_real_escape_string($conn, htmlspecialchars($_SESSION['id_usuario']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes</title>
</head>
<body>
    <?php

        $estado = 'solicitado';

        $sqlAmigos = "SELECT usuario1, usuario2, tbl_amigos.estado, usuario_amigo1.nombre AS amigo1, usuario_amigo2.nombre AS amigo2, usuario_amigo1.estado FROM tbl_amigos
        INNER JOIN tbl_usuarios AS usuario_amigo1 ON usuario_amigo1.id_usuario = tbl_amigos.usuario1
        INNER JOIN tbl_usuarios AS usuario_amigo2 ON usuario_amigo2.id_usuario = tbl_amigos.usuario2
        WHERE tbl_amigos.usuario1 = ? AND tbl_amigos.estado = ?";

        $stmtAmigos = mysqli_prepare($conn, $sqlAmigos);
        mysqli_stmt_bind_param($stmtAmigos, "is", $mi_usuario, $estado);
        mysqli_stmt_execute($stmtAmigos);
        mysqli_stmt_store_result($stmtAmigos);

        if (mysqli_stmt_num_rows($stmtAmigos) > 0) {

            mysqli_stmt_bind_result($stmtAmigos, $usuario1, $usuario2, $estadoAmistad, $nombreAmigo1, $nombreAmigo2, $estadoConexionActual);

            // Recorre cada mensaje y muestra el nombre del emisor o receptor según corresponda
            while (mysqli_stmt_fetch($stmtAmigos)) {     
                // Mostrar la lista de amigos
                echo "<p><strong>" . htmlspecialchars($nombreAmigo2) . "</strong></p>";
                echo '<form method="POST">
                        <input type="hidden" name="id_amigo" value="' . htmlspecialchars($usuario2) . '">
                        <input type="submit" value="Aceptar" name="Aceptar">
                        <input type="submit" value="Rechazar" name="Rechazar">
                    </form>';
            }
            if (isset($_POST['Aceptar'])) {

                $idAmigo = $_POST['id_amigo'];
                $estadoAmigo = 'amigo';
                $sqlRelacion = "UPDATE tbl_amigos SET estado = ? WHERE usuario2 = ?";
                $stmtRelacion = mysqli_prepare($conn, $sqlRelacion);
                mysqli_stmt_bind_param($stmtRelacion, "si", $estadoAmigo, $idAmigo);
                mysqli_stmt_execute($stmtRelacion);
                // Redireccionar después de procesar el formulario para evitar reenvío
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
                
            } elseif (isset($_POST['Rechazar'])) {

                $idAmigo = $_POST['id_amigo'];
                $estadoRechazado = 'rechazado';
                $sqlRelacion = "UPDATE tbl_amigos SET estado = ? WHERE usuario2 = ?";
                $stmtRelacion = mysqli_prepare($conn, $sqlRelacion);
                mysqli_stmt_bind_param($stmtRelacion, "si", $estadoRechazado, $idAmigo);
                mysqli_stmt_execute($stmtRelacion);
                // Redireccionar después de procesar el formulario para evitar reenvío
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();

            }

        } else {
            echo "<p>No tienes solicitudes</p>";
        }

    ?>
</body>

</html>
<?php
    }
?>