<?php
if(isset($_POST['query'])){
    $selector=0+$_POST['query'];
    $tipos=new Tipos($db);
    switch($selector){
        case 0://Insertar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['submit']);
            unset($datos['id']);
            unset($datos['controller']);
            $tipos->insertar($datos);
        break;
        case 1://Eliminar
            $id=$_POST['id'];
            $tipos->eliminar($id);
            echo "0";
        break;
        case 2://Eliminar Varios
            $ids=$_POST['id'];
            $tipos->eliminarVarios($ids);
            echo "0";
        break;
        case 3:
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['controller']);
            unset($datos['submit']);
            if(!isset($datos['activa'])) $datos['activa']=0;
            else  $datos['activa']=1;
            $tipos->modificar($datos);
        break;
        case 4://Get Tipo
            $id=$_POST['id'];
            $tipos->cargar();
            $tipo=$tipos->buscar("id", $id);
            print_r(json_encode($tipo));
        break;
    }
    if(!isset($_POST['ajax']) || !$_POST['ajax']) header("Location: ".$_SERVER['HTTP_REFERER']);
}