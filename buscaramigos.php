<?php

    include_once("./conexion.php");

    session_start();
    ob_start();

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
    <title>Buscar amigos</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body class="bodyBuscar">
    <header class="encabezado">
    <h1 style="padding-left:840px; font-size: 40px">Buscar amigos</h1>

    <div>

<a class="iconos" href="./logout.php?mi_usuario=<?php echo urlencode($mi_usuario) ?>" style="color: white;">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="black" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
            <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
        </svg>
    </a>
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

    <div class="buscarUser">
    <div class="divGrande">

    <form method="post" id="formBuscarUser">
        <input type="text" name="buscar_usuario" id="" placeholder="Buscar usuarios...">
        </div>
        <div class="divPequeño">
        <button type="submit" value="Buscar" name="Buscar" class="inputBuscar">Buscar</button>
    </form>
    </div>

    </div>

    <?php

        if (isset($_POST['Buscar'])) {
            
            // Obtiene el término de búsqueda enviado mediante el método POST
            $buscar_usuario = $_POST['buscar_usuario'];
            
            $inputBuscarUsuarios = '%' . $buscar_usuario . '%';

            // SELECCIONAMOS todos los usuarios, JUNTAMOS LAS FK de la tabla 'tbl_amigos'
            // (para más tarde mirar que usuarios son amigos, quienes no, y que usuarios ya les hemos enviado solicitud)
            // CUANDO la ID del usuario NO sea IGUAL que el usuario de nuestra sesión y sea IGUAL que el valor del input en el buscador
            // y AGRUPAMOS las filas que tengan los mismos valores (las mismas IDs de usuario)
            $sqlBuscador = "SELECT tbl_usuarios.id_usuario, nombre, apellidos, email, tbl_usuarios.estado, tbl_amigos.usuario1 AS amigo1, tbl_amigos.usuario2 AS amigo2, tbl_amigos.estado FROM tbl_usuarios
            LEFT JOIN tbl_amigos ON tbl_amigos.usuario1 = tbl_usuarios.id_usuario OR tbl_amigos.usuario2 = tbl_usuarios.id_usuario
            WHERE (tbl_usuarios.id_usuario != ?) AND (nombre LIKE ?)
            GROUP BY tbl_usuarios.id_usuario";

            $stmtBuscador = mysqli_prepare($conn, $sqlBuscador);
            mysqli_stmt_bind_param($stmtBuscador, "is", $mi_usuario, $inputBuscarUsuarios);
            mysqli_stmt_execute($stmtBuscador);
            mysqli_stmt_store_result($stmtBuscador);

            if (mysqli_stmt_num_rows($stmtBuscador) > 0) {
                echo "<div class=divAmigos>";

                mysqli_stmt_bind_result($stmtBuscador, $idUsuario, $nombre, $apellidos, $email, $estadoConexionActual, $usuario1, $usuario2, $estadoAmistad);

                // Recorre cada mensaje y muestra el nombre del emisor o receptor según corresponda
                while (mysqli_stmt_fetch($stmtBuscador)) {
                    echo "<div id=idamigo>";
                    if ($estadoAmistad == 'amigo') {
                        // Mostrar la lista de amigos
                        echo "<p><strong>" . htmlspecialchars($nombre) . "</strong> (".htmlspecialchars($estadoConexionActual).")</p>";
                        echo "<p> Ya és tu amigo. </p>";
                    }

                    if ($estadoAmistad == 'solicitado') {
                        // Mostrar la lista de amigos
                        echo "<p><strong>" . htmlspecialchars($nombre) . "</strong></p>";
                        echo "<p> Ya le has solicitado. </p>";
                        echo '<form method="POST" class="inputSolicitudes solicitar">
                            <input type="hidden" name="idUsuario" value="' . htmlspecialchars($idUsuario) . '">
                            <button type="submit" value="Cancelar Solicitud" class="botonSolicitud" name="Cancelar">Cancelar Solicitud</button>
                        </form>';
                    }

                    if ($estadoAmistad !== 'solicitado' && $estadoAmistad !== 'amigo' && $estadoAmistad !== 'rechazado') {
                        // Mostrar la lista de amigos
                        echo "<p><strong>" . htmlspecialchars($nombre) . "</strong></p>";
                        echo '<form method="POST">
                            <input type="hidden" name="idUsuario" value="' . htmlspecialchars($idUsuario) . '">
                            <button type="submit" value="Solicitar" class="botonSolicitud" name="Solicitar">Solicitar</button>

                        </form>';
                    }       
                    echo "</div>";
                }
                echo "</div>";

                if (isset($_POST['Solicitar'])) {

                    $idUsuario = $_POST['idUsuario'];
                    $estadoAmigo = 'solicitado';
                    $sqlRelacion = "INSERT INTO tbl_amigos (usuario1, usuario2, estado) VALUES (?, ?, ?)";
                    $stmtRelacion = mysqli_prepare($conn, $sqlRelacion);
                    mysqli_stmt_bind_param($stmtRelacion, "iis", $mi_usuario , $idUsuario, $estadoAmigo);
                    mysqli_stmt_execute($stmtRelacion);
                    // Redireccionar después de procesar el formulario para evitar reenvío
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                    
                }

                if (isset($_POST['Cancelar'])) {

                    $idUsuario = $_POST['idUsuario'];
                    $estadoAmigo = 'solicitado';
                    $sqlRelacion = "DELETE FROM tbl_amigos WHERE usuario1 = ? AND usuario2 = ? ";
                    $stmtRelacion = mysqli_prepare($conn, $sqlRelacion);
                    mysqli_stmt_bind_param($stmtRelacion, "ii", $mi_usuario, $idUsuario);
                    mysqli_stmt_execute($stmtRelacion);
                    // Redireccionar después de procesar el formulario para evitar reenvío
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();

                }

            } else {
                echo "<p>No hay usuarios disponibles.</p>";
            }


        } else {
            
            // SELECCIONAMOS todos los usuarios, JUNTAMOS LAS FK de la tabla 'tbl_amigos'
            // (para más tarde mirar que usuarios son amigos, quienes no, y que usuarios ya les hemos enviado solicitud)
            // CUANDO la ID del usuario NO sea IGUAL que el usuario de nuestra sesión
            // y AGRUPAMOS las filas que tengan los mismos valores (las mismas IDs de usuario)
            $sqlBuscarAmigos = "SELECT tbl_usuarios.id_usuario, nombre, apellidos, email, tbl_usuarios.estado, tbl_amigos.usuario1 AS amigo1, tbl_amigos.usuario2 AS amigo2, tbl_amigos.estado FROM tbl_usuarios
            LEFT JOIN tbl_amigos ON tbl_amigos.usuario1 = tbl_usuarios.id_usuario OR tbl_amigos.usuario2 = tbl_usuarios.id_usuario
            WHERE tbl_usuarios.id_usuario != ?
            GROUP BY tbl_usuarios.id_usuario";

            $stmtBuscarAmigos = mysqli_prepare($conn, $sqlBuscarAmigos);
            mysqli_stmt_bind_param($stmtBuscarAmigos, "i", $mi_usuario);
            mysqli_stmt_execute($stmtBuscarAmigos);
            mysqli_stmt_store_result($stmtBuscarAmigos);

            if (mysqli_stmt_num_rows($stmtBuscarAmigos) > 0) {
                echo "<div class=divAmigos>";

                mysqli_stmt_bind_result($stmtBuscarAmigos, $idUsuario, $nombre, $apellidos, $email, $estadoConexionActual, $usuario1, $usuario2, $estadoAmistad);

                // Recorre cada mensaje y muestra el nombre del emisor o receptor según corresponda
                while (mysqli_stmt_fetch($stmtBuscarAmigos)) {
                    echo "<div id=idamigo>";

                    if ($estadoAmistad == 'amigo') {
                        // Mostrar la lista de amigos
                        echo "<p><strong>" . htmlspecialchars($nombre) . "</strong> (".htmlspecialchars($estadoConexionActual).")</p>";
                        echo "<p> Ya és tu amigo. </p>";
                    }

                    if ($estadoAmistad == 'solicitado') {
                        // Mostrar la lista de amigos
                        echo "<p><strong>" . htmlspecialchars($nombre) . "</strong></p>";
                        echo "<p> Ya le has solicitado. </p>";
                        echo '<form method="POST" class="inputSolicitudes solicitar">
                            <input type="hidden" name="idUsuario" value="' . htmlspecialchars($idUsuario) . '">
                            <button type="submit" value="Cancelar Solicitud" class="botonSolicitud" name="Cancelar">Cancelar Solicitud</button>
                        </form>';
                    }

                    if ($estadoAmistad !== 'solicitado' && $estadoAmistad !== 'amigo' && $estadoAmistad !== 'rechazado') {
                        // Mostrar la lista de amigos
                        echo "<p><strong>" . htmlspecialchars($nombre) . "</strong></p>";
                        echo '<form method="POST">
                            <input type="hidden" name="idUsuario" value="' . htmlspecialchars($idUsuario) . '">
                            <button type="submit" value="Solicitar" class="botonSolicitud" name="Solicitar">Solicitar</button>
                        </form>';
                    }
                    echo "</div>";
                    echo "</div>";

                }

                if (isset($_POST['Solicitar'])) {

                    $idUsuario = $_POST['idUsuario'];
                    $estadoAmigo = 'solicitado';
                    $sqlRelacion = "INSERT INTO tbl_amigos (usuario1, usuario2, estado) VALUES (?, ?, ?)";
                    $stmtRelacion = mysqli_prepare($conn, $sqlRelacion);
                    mysqli_stmt_bind_param($stmtRelacion, "iis", $mi_usuario , $idUsuario, $estadoAmigo);
                    mysqli_stmt_execute($stmtRelacion);
                    // Redireccionar después de procesar el formulario para evitar reenvío
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                    
                }

                if (isset($_POST['Cancelar'])) {

                    $idUsuario = $_POST['idUsuario'];
                    $estadoAmigo = 'solicitado';
                    $sqlRelacion = "DELETE FROM tbl_amigos WHERE usuario1 = ? AND usuario2 = ? ";
                    $stmtRelacion = mysqli_prepare($conn, $sqlRelacion);
                    mysqli_stmt_bind_param($stmtRelacion, "ii", $mi_usuario, $idUsuario);
                    mysqli_stmt_execute($stmtRelacion);
                    // Redireccionar después de procesar el formulario para evitar reenvío
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();

                }

            } else {
                echo "<p>No hay usuarios disponibles.</p>";
            }
        }
        
        

    ?>
</body>
</html>
<?php
    }
    ob_end_flush();
?>