<?php
    session_start();
    if(isset($_SESSION['username'])){
        include_once "../conexion/conexion.php";
        $logout_id = $_GET['logout_id'];
        if(isset($logout_id)){
            $status = "Desconectado";
            $sql = "UPDATE tbl_users SET estado = :status WHERE username = :logout_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':status',$status);
            $stmt->bindParam(':logout_id',$logout_id);
            $stmt->execute();
            if($stmt){
                session_unset();
                session_destroy();
                header("location: ./login.php");
            }
        }else{
            header("location: ../index.php");
        }
    }else{
        header("location: ./login.php");
    }
?>