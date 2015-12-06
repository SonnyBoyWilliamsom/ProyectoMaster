<?php
if(isset($_POST['query'])){
    $selector=0+$_POST['query'];
    $zonas=new Zonas($db);
    switch($selector){
        case 0:
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['submit']);
            unset($datos['controller']);
            $provincias->insertar($datos);
        break;
        case 1:
            $id=$_POST['id'];
            $provincias->eliminar($id);
            echo "0";
        break;
        case 2:
            $ids=$_POST['id'];
            $provincias->eliminarVarios($ids);
            echo "0";
        break;
        case 3:
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['ajax']);
            echo $clientes->insertarClienteStatic($datos);
        break;
        case 4:
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['ajax']);
            unset($datos['submit']);
            $datos['id']=Clientes::getID();
            if($datos['obligatorio']=="on") $datos['obligatorio']="true";
            else $datos['obligatorio']="false";
            include_once("formularios.php");
            $provincias->nuevoCampo($campo);
        break;
        case 5: //Recuperar poblaciones
            header('Content-Type: text/json;charset=utf-8');
            $poblacion=new Poblaciones($db);
            $poblacion->establecerCodigoProvincia($_POST['codigo_sup']);
            $poblacion->cargar();
            print_r(json_encode($poblacion->obtenerRegistros()));
        break;
        case 6://Modificar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['controller']);
            unset($datos['submit']);
            if(!isset($datos['activa'])) $datos['activa']=0;
            else  $datos['activa']=1;
            $provincias->modificar($datos);
        break;
        case 7://Get Agente
            $id=$_POST['id'];
            $provincias->cargar();
            $provincia=$provincias->buscar("id", $id);
            print_r(json_encode($provincia));
        break;


        case 8:
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['submit']);
            unset($datos['controller']);
            $poblaciones->insertar($datos);
        break;
        case 9:
            $id=$_POST['id'];
            $poblaciones->eliminar($id);
            echo "0";
        break;
        case 10:
            $ids=$_POST['id'];
            $poblaciones->eliminarVarios($ids);
            echo "0";
        break;
        case 13: //Recuperar provincia
            $zonas->establecerCodigoPoblacion($_POST['codigo_sup']);
            $zonas->cargar();
            print_r(json_encode($zonas->obtenerRegistros()));
        break;
        case 14://Modificar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['controller']);
            unset($datos['submit']);

            if(!isset($datos['activa'])) $datos['activa']=0;
            else  $datos['activa']=1;
            if(!isset($datos['principal'])) $datos['principal']=0;
            else  $datos['principal']=1;

            $poblaciones->modificar($datos);
        break;
        case 15://Get provincia
            $poblaciones=new Poblaciones($db);
            $poblaciones->establecerCodigoProvincia(-1);
            $poblaciones->cargar();
            $poblacion=$poblaciones->buscar("id", $_POST['id']);
            print_r(json_encode($poblacion));
        break;







        case 16:
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['submit']);
            unset($datos['controller']);
            $zonas->insertar($datos);
        break;
        case 17:
            $id=$_POST['id'];
            $zonas->eliminar($id);
            echo "0";
        break;
        case 18:
            $ids=$_POST['id'];
            $zonas->eliminarVarios($ids);
            echo "0";
        break;
        case 21://Modificar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['controller']);
            unset($datos['submit']);
            if(!isset($datos['activa'])) $datos['activa']=0;
            else  $datos['activa']=1;
            $zonas->modificar($datos);
        break;
        case 22://Get Agente
            $zonas->cargar();
            $zona=$zonas->buscar("id", $_POST['id']);
            print_r(json_encode($zona));
        break;
    }
    if(!isset($_POST['ajax']) || !$_POST['ajax']) header("Location: ".$_SERVER['HTTP_REFERER']);
}
