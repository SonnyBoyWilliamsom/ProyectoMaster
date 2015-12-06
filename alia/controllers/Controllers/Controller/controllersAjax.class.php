<?php
if(isset($_POST['query'])){
    $selector=0+$_POST['query'];
    $controladores=new Controllers($db);
    unset($_POST['query']);
    switch($selector){
        case 0: //Insertar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['submit']);
            unset($datos['id']);
            $controladores->insertar($datos);
        break;	
        case 1: //Eliminar
            $id=$_POST['id'];
            $controladores->eliminar($id);
            echo "0";
        break;
        case 2: //Eliminar Varios
            $ids=$_POST['id'];
            $controladores->eliminarVarios($ids);
            echo "0";
        break;
        case 3: //Importar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['ajax']);
            echo $controladores->insertarStatic($datos);
        break;
        case 4://Nuevo Campo
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['ajax']);
            unset($datos['submit']);
            $datos['id']=Agentes::getID();
            include_once("formularios.php");
            $controladores->nuevoCampo($datos);
        break;
    }
    if(!isset($_POST['ajax']) || !$_POST['ajax']) header("Location: ".$_SERVER['HTTP_REFERER']);
}
