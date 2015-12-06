<?php
if(isset($_POST['query'])){
    $selector=0+$_POST['query'];
    $captacion=new Captacion($db);
    switch($selector){
        case 0: //Insertar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['submit']);
            unset($datos['controller']);
            $captacion->insertar($datos);
        break;
        case 1: //Eliminar
            $id=$_POST['id'];
            $captacion->eliminar($id);
            echo "0";
        break;
        case 2: //Eliminar Varios
            $ids=$_POST['id'];
            $captacion->eliminarVarios($ids);
            echo "0";
        break;
        case 3: //Importar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['ajax']);
            echo $captacion->insertarStatic($datos);
        break;
        case 4://Nuevo Campo
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['ajax']);
            unset($datos['submit']);
            $datos['id']=Agentes::getID();
            include_once("formularios.php");
            $captacion->nuevoCampo($datos);
        break;
        case 5://Modificar
            $captacion->modificar($datos);
        break;
        case 6://Get Inmueble
            include_once(getRoot()."/controllers/Captacion/Controller/captacion.class.php");
            $id=$_POST['id'];
            $captacion->cargar();
            $inmueble=$captacion->buscar("id", $id);
            print_r(json_encode($inmueble));
        break;
    }
    if(!isset($_POST['ajax']) || !$_POST['ajax']) header("Location: ".$_SERVER['HTTP_REFERER']);
}
?>
