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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú principal (Amigos)</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<script>
    function estado() {
        // Obtener todos los elementos con la clase "estado"
        const estados = document.getElementsByClassName("estado");

        // Recorrer todos los elementos con la clase "estado"
        for (let i = 0; i < estados.length; i++) {
            // Verificar si el contenido es 'en línea'
            if (estados[i].textContent === 'desconectado') {
                estados[i].style.color = "red"; // Cambiar el color a rojo
            }
        }
    }

    // Llama a la función estado cuando se carga la página
    window.onload = estado;
</script>

</head>
<body class="bodyLista">
    <header class="encabezado">
        <h1 style="padding-left:860px; font-size: 40px">Mis Amigos</h1>

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
            <a class="iconos" href="solicitudes.php" style="color: white;">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="black" class="bi bi-person-check-fill" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L12.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                    <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                </svg>
            </a>
            </div>

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
                    ?>
                        <div class="amigo">
                            <p><?php echo htmlspecialchars($nombreAmigo1); ?></p>
                            <p class="estado"><?php echo htmlspecialchars($estadoConexionActual); ?></p>
                            <div>
                                <a href="./chat.php?receptor=<?php echo urlencode(htmlspecialchars($usuario1)); ?>" style="color: white;padding:0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="bi bi-chat-dots-fill" viewBox="0 0 16 16">
                                        <path d="M16 8c0 3.866-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7M5 8a1 1 0 1 0-2 0 1 1 0 0 0 2 0m4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <?php

                    } elseif ($usuario2 != $mi_usuario) {
                        
                        ?>
                        <div class="amigo">
                            <p><?php echo htmlspecialchars($nombreAmigo2); ?></p>
                            <p class="estado"><?php echo htmlspecialchars($estadoConexionActual); ?></p>
                            <div>
                                <a href="./chat.php?receptor=<?php echo urlencode(htmlspecialchars($usuario2)); ?>" style="color: white;padding:0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="bi bi-chat-dots-fill" viewBox="0 0 16 16">
                                        <path d="M16 8c0 3.866-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7M5 8a1 1 0 1 0-2 0 1 1 0 0 0 2 0m4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <?php
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