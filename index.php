<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de session</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="contenedor">
        <form action="./validacion.php" method="post">
            <h2>Iniciar sesión</h2>
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre">
            <?php if(isset($_GET['usernameVacio'])) {
                echo '<span class="error">Nombre vacio</span>';
            } ?>
            <label for="pwd">Contraseña:</label>
            <input type="password" name="pwd" id="pwd">
            <?php if(isset($_GET['passwordVacio'])) {
                echo '<span class="error">Contraseña vacia</span>';
            }?>
            <button type="submit" name="enviar">Siguiente</button>
            <?php if(isset($_GET['usernameMal']) or isset($_GET['passwordMal'])) {
                echo '<span class="error">Usuario o contraseña incorrectos</span>';
            }?>
            <br><br>
            <a href="registrar.php">¿No estás registrado?</a>
        </form>
    </div>
</body>


</html>
