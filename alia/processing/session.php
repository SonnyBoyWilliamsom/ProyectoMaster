<?php
    session_start() or die("Error comprobando la sesion");
    if(!isset($_SESSION['usuario'])){
        header('Location: login');
    }
?>