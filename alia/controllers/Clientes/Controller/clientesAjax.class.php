<?php
/*Controller*/
if(isset($_POST['query'])){
    $selector=0+$_POST['query'];
    unset($_POST['query']);
    $clientes=new Clientes($db) ;
    switch($selector){
        case 0: //Insertar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['submit']);
            unset($datos['id']);
            unset($datos['controller']);
            $clientes->insertar($datos);
            if(isset($_POST['ajax']) && $_POST['ajax']==true){
                $id=queryBD("select max(id) as id from clientes", $db);
                $id=$id[0];
                echo $id['id'];
            }
        break;
        case 1: //Eliminar
            $id=$_POST['id'];
            $clientes->eliminar($id);
            echo "0";
        break;
        case 2: //Eliminar Varios
            $ids=$_POST['id'];
            $clientes->eliminarVarios($ids);
            echo "0";
        break;
        case 3: //Importar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['ajax']);
            echo $clientes->insertarClienteStatic($datos);
        break;
        case 4://Nuevo Campo
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['ajax']);
            unset($datos['submit']);
            $datos['id']=Clientes::getID();
            if($datos['obligatorio']=="on") $datos['obligatorio']="true";
            else $datos['obligatorio']="false";
            include_once("formularios.php");
            $clientes->nuevoCampo($datos);
        break;
        case 5://Modificar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['controller']);
            unset($datos['submit']);
            if(!isset($datos['vendedor'])) $datos['vendedor']=0;
            else  $datos['vendedor']=1;
            if(!isset($datos['comprador'])) $datos['comprador']=0;
            else  $datos['comprador']=1;
            if(!isset($datos['arrendatario'])) $datos['arrendatario']=0;
            else  $datos['arrendatario']=1;
            if(!isset($datos['inquilino'])) $datos['inquilino']=0;
            else  $datos['inquilino']=1;
            $clientes->modificar($datos);
        break;
        case 6://Get Agente
            $id=$_POST['id'];
            $clientes->cargar();
            $cliente=$clientes->buscar("id", $id);
            print_r(json_encode($cliente));
        break;
        case 7://Búsqueda avanzada
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['controller']);
            unset($datos['submit']);
            unset($datos['ajax']);
            print_r(json_encode($clientes->buscadorAvanzado($datos)));
            die();
        break;
    }
    if(!isset($_POST['ajax']) || !$_POST['ajax']) header("Location: ".$_SERVER['HTTP_REFERER']);
}
?>
