<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="contenedor" id="registroContenedor">
        <h2 id="formTitulo">Registrar Usuario</h2>
        <form id="formRegistro" action="registro.php" method="post">
            <label for="nombreRegistrar" id="labelnombreRegistrar">Nombre</label>
            <input type="text" id="nombreRegistrar" name="nombreRegistrar" 
                   value="<?php echo isset($_GET['nombreRegistrar']) ? htmlspecialchars($_GET['nombreRegistrar']) : ''; ?>">
            <?php if(isset($_GET['nombreVacio'])) {
                echo '<span class="error">Nombre vacío</span>';
            } elseif (isset($_GET['nombreCorto'])) {
                echo '<span class="error">El nombre debe tener al menos 3 caracteres.</span>';
            } elseif (isset($_GET['nombreInvalido'])) {
                echo '<span class="error">El nombre no debe contener números/símbolos.</span>';
            }?>
            
            <label for="apellidoRegistrar" id="labelapellidoRegistrar">Apellidos</label>
            <input type="text" id="apellidoRegistrar" name="apellidoRegistrar" 
                   value="<?php echo isset($_GET['apellidoRegistrar']) ? htmlspecialchars($_GET['apellidoRegistrar']) : ''; ?>">
            <?php if(isset($_GET['apellidoVacio'])) {
                echo '<span class="error">Apellido vacío</span>';
            } elseif (isset($_GET['apellidoInvalido'])) {
                echo '<span class="error">El apellido debe tener al menos 3 caracteres y no contener números/símbolos.</span>';
            } elseif (isset($_GET['apellidoSeparado'])) {
                echo '<span class="error">Se requieren dos apellidos separados por un espacio.</span>';
            }?>
            
            <label for="emailRegistrar" id="labelemailRegistrar">Email</label>
            <input type="text" id="emailRegistrar" name="emailRegistrar" 
                   value="<?php echo isset($_GET['emailRegistrar']) ? htmlspecialchars($_GET['emailRegistrar']) : ''; ?>">
            <?php if(isset($_GET['emailVacio'])) {
                echo '<span class="error">Email vacío</span>';
            } elseif (isset($_GET['emailInvalido'])) {
                echo '<span class="error">Email no válido.</span>';
            }?>
            
            <label for="registrarContrasena" id="labelRegistrarContrasena">Contraseña</label>
            <input type="password" id="registrarContrasena" name="registrarContrasena">
            <?php if(isset($_GET['passwordVacio'])) {
                echo '<span class="error">Contraseña vacía</span>';
            } elseif (isset($_GET['passwordInvalida'])) {
                echo '<span class="error">La contraseña debe contener al menos una mayúscula, una minúscula y un número.</span>';
            }?>

            <label for="registrarRepetirContrasena" id="labelRegistrarRepetirContrasena">Repetir Contraseña</label>
            <input type="password" id="registrarRepetirContrasena" name="registrarRepetirContrasena">
            <?php if(isset($_GET['repetirPassword'])) {
                echo '<span class="error">Las contraseñas no coinciden</span>';
            }
            ?>

            <button type="submit" id="btnEnviar" name="enviar2">Registrarse</button>
            <br><br>
            <a href="./index.php" id="volver">Volver</a>
        </form>
    </div>
</body>
</html>
