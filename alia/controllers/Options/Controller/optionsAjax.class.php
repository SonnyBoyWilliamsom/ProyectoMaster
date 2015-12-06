<?php
if(isset($_POST['query'])){
    $selector=0+$_POST['query'];
    $options=new Options($db);
    switch($selector){
        case 0://Insertar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['submit']);
            unset($datos['id']);
            unset($datos['controller']);
            $options->insertar($datos,$db);
        break;
        case 1://Eliminar
            $id=$_POST['id'];
            $options->eliminar($id);
            echo "0";
        break;
        case 2://Eliminar Varios
            $ids=$_POST['id'];
            $options->eliminarVarios($ids);
            echo "0";
        break;
    }
    if(!isset($_POST['ajax']) || !$_POST['ajax']) header("Location: ".$_SERVER['HTTP_REFERER']);
}