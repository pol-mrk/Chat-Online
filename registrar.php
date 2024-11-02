<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="contenedor" id="registroContenedor">
        <h2 id="formTitulo">Registrar Usuario</h2>
        <form id="formRegistro" action="registro.php" method="post">
            <label for="registrarNombre" id="labelRegistrarNombre">Nombre</label>
            <input type="text" id="registrarNombre" name="registrarNombre" 
                   value="<?php echo isset($_GET['registrarNombre']) ? htmlspecialchars($_GET['registrarNombre']) : ''; ?>">
            <?php if(isset($_GET['nombreVacio'])) {
                echo '<span class="error">Nombre vacío</span>';
            } elseif (isset($_GET['nombreCorto'])) {
                echo '<span class="error">El nombre debe tener al menos 3 caracteres.</span>';
            } elseif (isset($_GET['nombreInvalido'])) {
                echo '<span class="error">El nombre no debe contener números/símbolos.</span>';
            }?>
            
            <label for="registrarApellido" id="labelRegistrarApellido">Apellido</label>
            <input type="text" id="registrarApellido" name="registrarApellido" 
                   value="<?php echo isset($_GET['registrarApellido']) ? htmlspecialchars($_GET['registrarApellido']) : ''; ?>">
            <?php if(isset($_GET['apellidoVacio'])) {
                echo '<span class="error">Apellido vacío</span>';
            } elseif (isset($_GET['apellidoInvalido'])) {
                echo '<span class="error">El apellido debe tener al menos 3 caracteres y no contener números/símbolos.</span>';
            } elseif (isset($_GET['apellidoSeparado'])) {
                echo '<span class="error">Se requieren dos apellidos separados por un espacio.</span>';
            }?>
            
            <label for="registrarEmail" id="labelRegistrarEmail">Email</label>
            <input type="email" id="registrarEmail" name="registrarEmail" 
                   value="<?php echo isset($_GET['registrarEmail']) ? htmlspecialchars($_GET['registrarEmail']) : ''; ?>">
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

            <button type="submit" id="btnEnviar" name="enviar2">Registrarse</button>
            <br><br>
            <a href="./index.php" id="volver">Volver</a>
        </form>
    </div>
</body>
</html>
