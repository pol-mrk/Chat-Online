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
    <title>Solicitar amigos</title>
</head>
<body>
    <form method="post">
        <input type="text" name="buscar_usuario" id="" placeholder="Buscar usuarios...">
        <input type="submit" value="Buscar" name="Buscar">
    </form>
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

                mysqli_stmt_bind_result($stmtBuscador, $idUsuario, $nombre, $apellidos, $email, $estadoConexionActual, $usuario1, $usuario2, $estadoAmistad);

                // Recorre cada mensaje y muestra el nombre del emisor o receptor según corresponda
                while (mysqli_stmt_fetch($stmtBuscador)) {
                    
                    if ($estadoAmistad == 'amigo') {
                        // Mostrar la lista de amigos
                        echo "<p><strong>" . htmlspecialchars($nombre) . "</strong> (".htmlspecialchars($estadoConexionActual).")</p>";
                        echo "<p> Ya és tu amigo. </p>";
                    }

                    if ($estadoAmistad == 'solicitado') {
                        // Mostrar la lista de amigos
                        echo "<p><strong>" . htmlspecialchars($nombre) . "</strong></p>";
                        echo "<p> Ya le has solicitado. </p>";
                        echo '<form method="POST">
                            <input type="hidden" name="idUsuario" value="' . htmlspecialchars($idUsuario) . '">
                            <input type="submit" value="Cancelar Solicitud" name="Cancelar">
                        </form>';
                    }

                    if ($estadoAmistad !== 'solicitado' && $estadoAmistad !== 'amigo' && $estadoAmistad !== 'rechazado') {
                        // Mostrar la lista de amigos
                        echo "<p><strong>" . htmlspecialchars($nombre) . "</strong></p>";
                        echo '<form method="POST">
                            <input type="hidden" name="idUsuario" value="' . htmlspecialchars($idUsuario) . '">
                            <input type="submit" value="Solicitar" name="Solicitar">
                        </form>';
                    }       

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

                mysqli_stmt_bind_result($stmtBuscarAmigos, $idUsuario, $nombre, $apellidos, $email, $estadoConexionActual, $usuario1, $usuario2, $estadoAmistad);

                // Recorre cada mensaje y muestra el nombre del emisor o receptor según corresponda
                while (mysqli_stmt_fetch($stmtBuscarAmigos)) {
                    
                    if ($estadoAmistad == 'amigo') {
                        // Mostrar la lista de amigos
                        echo "<p><strong>" . htmlspecialchars($nombre) . "</strong> (".htmlspecialchars($estadoConexionActual).")</p>";
                        echo "<p> Ya és tu amigo. </p>";
                    }

                    if ($estadoAmistad == 'solicitado') {
                        // Mostrar la lista de amigos
                        echo "<p><strong>" . htmlspecialchars($nombre) . "</strong></p>";
                        echo "<p> Ya le has solicitado. </p>";
                        echo '<form method="POST">
                            <input type="hidden" name="idUsuario" value="' . htmlspecialchars($idUsuario) . '">
                            <input type="submit" value="Cancelar Solicitud" name="Cancelar">
                        </form>';
                    }

                    if ($estadoAmistad !== 'solicitado' && $estadoAmistad !== 'amigo' && $estadoAmistad !== 'rechazado') {
                        // Mostrar la lista de amigos
                        echo "<p><strong>" . htmlspecialchars($nombre) . "</strong></p>";
                        echo '<form method="POST">
                            <input type="hidden" name="idUsuario" value="' . htmlspecialchars($idUsuario) . '">
                            <input type="submit" value="Solicitar" name="Solicitar">
                        </form>';
                    }
                    

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
?>