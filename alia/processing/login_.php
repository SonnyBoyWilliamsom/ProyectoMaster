<?php
include_once("library.php");
$root=getRoot();
include_once $root."/controllers/Base/Controller/base.class.php";
include_once($root."/controllers/Agentes/Controller/agentes.class.php");
if($_POST['submit']=="Acceder"){
    $db=connectBD();
    $aBuscar=array("\"","'");
    $reemplazo=array("","");
    $mail=str_replace($aBuscar,$reemplazo,$_POST['mail']);
    $pass=md5($_POST['pass']);
    $resultado=queryBD("select * from agentes where mail=\"$mail\" and password=\"$pass\" and activacion=true",$db);
    if(count($resultado)>0){
        $agentes=new Agentes($db);
        $agentes->cargar();
        session_start();
        $_SESSION['usuario']=$agentes->buscar('id',$resultado[0]['id']);
        header('Location: ../admin/');
    }
    else header('Location: ../admin/login.php?e=1');
    disconnectBD($db);
}
else{
    header('Location: ../admin/login.php');
}
?>