<?php
if(isset($_POST['query'])){
    $selector=0+$_POST['query'];
    $gestiones=new Gestiones($db);
    switch($selector){
        case 0: //Insertar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['submit']);
            unset($datos['id']);
            unset($datos['controller']);
            $gestiones->insertar($datos);
        break;	
        case 1: //Eliminar
            $id=$_POST['id'];
            $gestiones->eliminar($id);
            echo "0";
        break;
        case 2: //Eliminar Varios
            $ids=$_POST['id'];
            $gestiones->eliminarVarios($ids);
            echo "0";
        break;
        case 3: //Importar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['ajax']);
            $gestiones->insertarStatic($datos);
        break;
        case 4://Nuevo Campo
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['ajax']);
            unset($datos['submit']);
            $datos['id']=Gestiones::getID();
            include_once("formularios.php");
            $gestiones->nuevoCampo($datos,$db);
        break;
        case 5://Modificar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['controller']);
            unset($datos['submit']);
            $gestiones->modificar($datos);
        break;
        case 6://Get Agente
            $id=$_POST['id'];
            $gestiones->cargar();
            $gestion=$gestiones->buscar("id", $id);
            print_r(json_encode($gestion));
        break;
    }
    if(!isset($_POST['ajax']) || !$_POST['ajax']) header("Location: ".$_SERVER['HTTP_REFERER']);
}
?>