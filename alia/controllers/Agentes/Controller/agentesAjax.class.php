<?php
if(isset($_POST['query'])){
    $selector=0+$_POST['query'];
    $agentes=new Agentes($db);
    switch($selector){
        case 0: //Insertar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['submit']);
            unset($datos['id']);
            unset($datos['controller']);
            $agentes->insertar($datos);
        break;
        case 1: //Eliminar
            $id=$_POST['id'];
            $agentes->eliminar($id);
            echo "0";
        break;
        case 2: //Eliminar Varios
            $ids=$_POST['id'];
            $agentes->eliminarVarios($ids);
            echo "0";
        break;
        case 3: //Importar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['ajax']);
            echo $agentes->insertar($datos);
        break;
        case 4://Nuevo Campo
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['ajax']);
            unset($datos['submit']);
            $datos['id']=$agentes->getID();
            $agentes->nuevoCampo($datos);
        break;
        case 5://Login
            $aBuscar=array("\"","'");
            $reemplazo=array("","");
            $usuario=str_replace($aBuscar,$reemplazo,$_POST['usuario']);
            $pass=$_POST['password'];
            $resultado=queryBD("select * from agentes where usuario=\"$usuario\" and password=MD5(\"$pass\")",$db);
            if(count($resultado)>0){
                $agentes->cargar();
                session_start();
                $_SESSION['usuario']=$agentes->buscar('id',$resultado[0]['id']);
                header('Location: ../admin');
                die();
            }
        break;
        case 6://Modificar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['controller']);
            unset($datos['submit']);
            if(strlen($datos['password'])==0) unset($datos['password']);
            else $datos['password']=md5($datos['password']);

            $agentes->modificar($datos);
        break;
        case 7://Get Agente
            $id=$_POST['id'];
            $agentes->cargar();
            $agente=$agentes->buscar("id", $id);
            unset($agente['password']);
            print_r(json_encode($agente));
        break;
    }
    if(!isset($_POST['ajax']) || !$_POST['ajax']) header("Location: ".$_SERVER['HTTP_REFERER']);
}
?>
