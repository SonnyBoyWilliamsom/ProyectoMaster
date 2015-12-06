<?php
if(isset($_POST['query'])){
    $selector=0+$_POST['query'];
    $empresass=new Empresas($db);
    switch($selector){
        case 0: //Insertar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['submit']);
            unset($datos['id']);
            unset($datos['controller']);
            $empresass->insertar($datos);
        break;
        case 1: //Eliminar
            $id=$_POST['id'];
            $empresass->eliminar($id);
            echo "0";
        break;
        case 2: //Eliminar Varios
            $ids=$_POST['id'];
            $empresass->eliminarVarios($ids);
            echo "0";
        break;
        case 3: //Importar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['ajax']);
            echo $empresass->insertar($datos);
        break;
        case 4://Nuevo Campo
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['ajax']);
            unset($datos['submit']);
            $datos['id']=$empresass->getID();
            $empresass->nuevoCampo($datos);
        break;
        case 5://Login
            $aBuscar=array("\"","'");
            $reemplazo=array("","");
            $usuario=str_replace($aBuscar,$reemplazo,$_POST['usuario']);
            $pass=$_POST['password'];
            $resultado=queryBD("select * from agentes where usuario=\"$usuario\" and password=MD5(\"$pass\")",$db);
            if(count($resultado)>0){
                $empresass->cargar();
                session_start();
                $_SESSION['usuario']=$empresass->buscar('id',$resultado[0]['id']);
                header('Location: ../admin');
                die();
            }
        break;
        case 6://Modificar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['controller']);
            unset($datos['submit']);
            $empresass->modificar($datos);
        break;
        case 7://Get Agente
            $id=$_POST['id'];
            $empresass->cargar();
            $empresas=$empresass->buscar("id", $id);
            unset($empresas['password']);
            print_r(json_encode($empresas));
        break;
        case 8:
            $id=$_POST['id'];
            $query="update empresas set destacado=0";
            queryBD($query,$db);
            $query="update empresas set destacado=1 where id=$id";
            queryBD($query,$db);
        break;
    }
    if(!isset($_POST['ajax']) || !$_POST['ajax']) header("Location: ".$_SERVER['HTTP_REFERER']);
}
?>
