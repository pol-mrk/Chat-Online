<?php
    $error="";
    function validaCampoVacio($campo) {
        if(empty($campo)){
            $error= true; //Hay un error
        }else{
            $error= false; //No hay un error
        }
        return $error;
    }

    function validarContrasena_8caracteres($contrasena) {

        // Verifica que la longitud sea al menos de 8 caracteres
        if (strlen($contrasena) < 8) {
            $error=true; // Hay un error
        } else {
            $error=false; // No hay un error
        }

    return $error;
    }

    function validarContrasena_letraMayus($contrasena) {

        // Verifica que contenga al menos una letra mayúscula
        if (preg_match("/[A-Z]/", $contrasena)) {
            $error=true; // Hay un error
        } else {
            $error=false; // No hay un error
        }

    return $error;
    }

    function validarContrasena_caractSpecial($contrasena) {

        // Verifica que contenga al menos un carácter especial
        if (preg_match('/[\W]/', $contrasena)) {
            $error=true; // Hay un error
        } else {
            $error=false; // No hay un error
        }

    return $error;
    }

    function validarFormatoEmail($email) {

        // Patrón para verificar el email con al menos 3 caracteres después del @ y al menos 2 caracteres después del último .
        $verificacion = "/^[\w\.-]+@[a-zA-Z\d-]{3,}\.[a-zA-Z]{2,}$/";
    
        // Verificar si el correo coincide con el patrón
        if (preg_match($verificacion, $email)) {
            return false; // No hay error
        } else {
            return true; // Hay un error
        }
    }

?>

<?php
if (!filter_has_var(INPUT_POST, 'register')) {
    header('Location: '.'./register.proc.php');
    exit();
} else {

$errores="";

$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$email = $_POST['email'];
$contrasena = $_POST['contrasena'];
$reptr_contrasena = $_POST['reptr_contrasena'];


if (validaCampoVacio($nombre)){
    if (!$errores){
        $errores .="?usernameVacio=true";
     } else {
        $errores .="&usernameVacio=true";        
     }
  } else {
    if(!preg_match("/^[a-zA-Z0-9]*$/",$nombre)){
        if (!$errores){
            $errores .="?usernameMal=true";
         } else {
            $errores .="&usernameMal=true";        
         }
    }
}

if (validaCampoVacio($apellidos)){
    if (!$errores){
        $errores .="?apellidoVacio=true";
     } else {
        $errores .="&apellidoVacio=true";        
     }
  } else {
    if(!preg_match("/^[A-Z][a-z]+ [A-Z][a-z]+$/",$apellidos)){
        if (!$errores){
            $errores .="?apellidoMal=true";
         } else {
            $errores .="&apellidoMal=true";        
         }
    }
}

if (validaCampoVacio($email)){

    if (!$errores){
        $errores .="?emailVacio=true";
    } else {
        $errores .="&emailVacio=true";        
    }

} else {

    if(validarFormatoEmail($email)){
        if (!$errores){
            $errores .="?emailMal=true";
        } else {
            $errores .="&emailMal=true";        
        }
    }

}

if (validaCampoVacio($contrasena)){

    if (!$errores){
        $errores .="?contrasenaVacio=true";
    } else {
        $errores .="&contrasenaVacio=true";        
    }

} elseif (validarContrasena_8caracteres($contrasena)) {

    if (!$errores) {
        $errores .= "?passwordMal8car=true";
    } else {
        $errores .= "&passwordMal8car=true";
    }

} elseif (!validarContrasena_letraMayus($contrasena)) {

    if (!$errores) {
        $errores .= "?passwordMalMayus=true";
    } else {
        $errores .= "&passwordMalMayus=true";
    }

} else {

    if (!validarContrasena_caractSpecial($contrasena)) {
        if (!$errores) {
            $errores .= "?passwordMalSpecCar=true";
        } else {
            $errores .= "&passwordMalSpecCar=true";
        }
    }

} 

if (validaCampoVacio($reptr_contrasena)){

    if ($contrasena != "") {

        if (!$errores){
            $errores .="?contrasena2Vacio=true";
        } else {
            $errores .="&contrasena2Vacio=true";        
        }

    } else {

        if(!preg_match("/^[a-zA-Z0-9]*$/",$reptr_contrasena)){
            if (!$errores){
                $errores .="?contrasena2Mal=true";
            } else {
                $errores .="&contrasena2Mal=true";        
            }
        }
        else if ($contrasena != $reptr_contrasena) {
            if (!$errores){
                $errores .="?contrasena2Repetir=true";
            } else {
                $errores .="&contrasena2Repetir=true";        
            }
        }

    }   
}

if ($errores!=""){

    $datosRecibidos = array(
        'nombre' => $nombre,
        'apellidos'=> $apellidos,
        'email' => $email,
        'contrasena' => $contrasena,
        'conf_contrasena' => $reptr_contrasena 
    );
    
    $datosDevueltos=http_build_query($datosRecibidos);
    header("Location: ../register.php". $errores. "&". $datosDevueltos);
    exit();
}else{
    echo"<form id='EnvioCheck' action='register.proc.php' method='POST'>";
    echo"<input type='hidden' id='nombre' name='nombre' value='".$nombre."'>";
    echo"<input type='hidden' id='apellidos' name='apellidos' value='".$apellidos."'>";
    echo"<input type='hidden' id='email' name='email' value='".$email."'>";
    echo"<input type='hidden' id='contrasena' name='contrasena' value='".$contrasena."'>";
    echo"<input type='hidden' id='reptr_contrasena' name='reptr_contrasena' value='".$reptr_contrasena."'>";
    echo"</form>";
    echo "<script>document.getElementById('EnvioCheck').submit();</script>";
 }
}

