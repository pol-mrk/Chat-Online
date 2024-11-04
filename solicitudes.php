<?php

include_once("./conexion.php");

session_start();

if (!isset($_SESSION['loggedin']) && !isset($_SESSION['id_usuario'])) {
    header('Location: ' . './index.php');
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
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body class="bodyBuscar">
<header class="encabezado">
        <h1 style="padding-left:750px; font-size: 40px">Solicitudes pendientes</h1>
        <div>
            <a class="iconos" href="./logout.php?mi_usuario=<?php echo urlencode($mi_usuario) ?>" style="color: white;">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="black" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                </svg>
            </a>
            <a class="iconos" href="buscaramigos.php" style="color: white;">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="black" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                    <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                    <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5"/>
                </svg>
            </a>
            <a class="iconos" href="amigos.php" style="color: white;">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="black" class="bi bi-people-fill" viewBox="0 0 16 16">
                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                </svg>
            </a>
        </div>
</header>
<div class="contenedor" id="amigosContainer">
    <?php
        $estado = 'solicitado';

        // Cambié el WHERE para obtener solicitudes donde el usuario es usuario2
        $sqlAmigos = "SELECT usuario1, usuario2, tbl_amigos.estado, usuario_amigo1.nombre AS amigo1, usuario_amigo2.nombre AS amigo2 
        FROM tbl_amigos
        INNER JOIN tbl_usuarios AS usuario_amigo1 ON usuario_amigo1.id_usuario = tbl_amigos.usuario1
        INNER JOIN tbl_usuarios AS usuario_amigo2 ON usuario_amigo2.id_usuario = tbl_amigos.usuario2
        WHERE tbl_amigos.usuario2 = ? AND tbl_amigos.estado = ?";

        $stmtAmigos = mysqli_prepare($conn, $sqlAmigos);
        mysqli_stmt_bind_param($stmtAmigos, "is", $mi_usuario, $estado);
        mysqli_stmt_execute($stmtAmigos);
        mysqli_stmt_store_result($stmtAmigos);

        if (mysqli_stmt_num_rows($stmtAmigos) > 0) {
            mysqli_stmt_bind_result($stmtAmigos, $usuario1, $usuario2, $estadoAmistad, $nombreAmigo1, $nombreAmigo2);

            // Recorre cada mensaje y muestra el nombre del emisor
            while (mysqli_stmt_fetch($stmtAmigos)) {     
                // Mostrar la lista de amigos
                echo "<div class='amigo' id='solicitudAmigo'>";
                echo "<p><strong>" . htmlspecialchars($nombreAmigo1) . "</strong></p>"; // Usamos nombreAmigo1 para mostrar el que envió la solicitud
                echo '<form method="POST">
                        <input type="hidden" name="id_amigo" value="' . htmlspecialchars($usuario1) . '">
                        <button type="submit" name="Aceptar" class="inputSolicitudes">Aceptar</button>
                        <button type="submit" name="Rechazar" class="inputSolicitudes">Rechazar</button>
                    </form>';
                echo "</div>";
            }

            if (isset($_POST['Aceptar'])) {
                $idAmigo = $_POST['id_amigo'];
                $estadoAmigo = 'amigo';
                $sqlRelacion = "UPDATE tbl_amigos SET estado = ? WHERE usuario1 = ? AND usuario2 = ?";
                $stmtRelacion = mysqli_prepare($conn, $sqlRelacion);
                mysqli_stmt_bind_param($stmtRelacion, "ssi", $estadoAmigo, $idAmigo, $mi_usuario);
                mysqli_stmt_execute($stmtRelacion);
                // Redireccionar después de procesar el formulario para evitar reenvío
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
                
            } elseif (isset($_POST['Rechazar'])) {
                $idAmigo = $_POST['id_amigo'];
                $estadoRechazado = 'rechazado';
                $sqlRelacion = "UPDATE tbl_amigos SET estado = ? WHERE usuario1 = ? AND usuario2 = ?";
                $stmtRelacion = mysqli_prepare($conn, $sqlRelacion);
                mysqli_stmt_bind_param($stmtRelacion, "ssi", $estadoRechazado, $idAmigo, $mi_usuario);
                mysqli_stmt_execute($stmtRelacion);
                // Redireccionar después de procesar el formulario para evitar reenvío
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        } else {
            echo "<p id='sinAmigos'>No tienes solicitudes</p>";
        }
    ?>
</div>
</body>
</html>
<?php
}
?>
