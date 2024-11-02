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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú principal (Amigos)</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Mis Amigos</h1>
    </header>
    <div class="container">
        <?php
            $estado = 'amigo';

            $sqlAmigos = "SELECT usuario1, usuario2, tbl_amigos.estado, usuario_amigo1.nombre AS amigo1, usuario_amigo2.nombre AS amigo2, usuario_amigo1.estado FROM tbl_amigos
            INNER JOIN tbl_usuarios AS usuario_amigo1 ON usuario_amigo1.id_usuario = tbl_amigos.usuario1
            INNER JOIN tbl_usuarios AS usuario_amigo2 ON usuario_amigo2.id_usuario = tbl_amigos.usuario2
            WHERE (tbl_amigos.usuario1 = ? OR tbl_amigos.usuario2 = ?) AND tbl_amigos.estado = ?";

            $stmtAmigos = mysqli_prepare($conn, $sqlAmigos);
            mysqli_stmt_bind_param($stmtAmigos, "iis", $mi_usuario, $mi_usuario, $estado);
            mysqli_stmt_execute($stmtAmigos);
            mysqli_stmt_store_result($stmtAmigos);

            if (mysqli_stmt_num_rows($stmtAmigos) > 0) {
                mysqli_stmt_bind_result($stmtAmigos, $usuario1, $usuario2, $estadoAmistad, $nombreAmigo1, $nombreAmigo2, $estadoConexionActual);

                // Recorre cada mensaje y muestra el nombre del emisor o receptor según corresponda
                while (mysqli_stmt_fetch($stmtAmigos)) {
                    // Mostrar la lista de amigos
                    if ($usuario1 != $mi_usuario) {
                        echo "<div class='amigo'><a href='./chat.php?receptor=" . urlencode(htmlspecialchars($usuario1)) . "'>" . htmlspecialchars($nombreAmigo1) . "</a>";
                        echo "<p class='estado'>" . htmlspecialchars($estadoConexionActual) . "</p></div>";
                    } elseif ($usuario2 != $mi_usuario) {
                        echo "<div class='amigo'><a href='./chat.php?receptor=" . urlencode(htmlspecialchars($usuario2)) . "'>" . htmlspecialchars($nombreAmigo2) . "</a>";
                        echo "<p class='estado'>" . htmlspecialchars($estadoConexionActual) . "</p></div>";
                    }
                }
            } else {
                echo "<p>No tienes amigos xd</p>";
            }
        ?>
    </div>
</body>
</html>
<?php
    }
?>
