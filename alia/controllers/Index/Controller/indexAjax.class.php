<?php
ini_set("display_errors",1);
if(isset($_POST['query'])){
    $selector=0+$_POST['query'];
    $index=new Index($db);
    switch($selector){
        case 0:
            include_once(getRoot()."/controllers/Mail/Controller/mail.class.php");
            $config=getConfiguration("Compania");
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['controller']);
            unset($datos['submit']);
            $mail=new Mail();
            $mail->setRemiter($datos["mail"],$datos["nombre"]);
            $mail->setDestiny($config["correo"],$config['nombre']);
            $mail->setMessage($datos,"Contacto","contacto");
            $correcto=$mail->send();
            if($correcto) header("Location: ".getUrl()."/envio-correcto.php");
            else header("Location: ".getUrl()."/envio-fallido.php");
            die();
        break;
        case 1:
            include_once(getRoot()."/controllers/Mail/Controller/mail.class.php");
            $config=getConfiguration("Compania");
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['controller']);
            unset($datos['submit']);
            $mail=new Mail();
            $mail->setRemiter($datos["mail"],$datos["nombre"]);
            $mail->setDestiny($config["correo"],$config['nombre']);
            $mail->setMessage($datos,"Asóciate","asociate");
            $correcto=$mail->send();
            if($correcto) header("Location: ".getUrl()."/envio-correcto.php");
            else header("Location: ".getUrl()."/envio-fallido.php");
            die();
        break;
        case 2:
            include_once(getRoot()."/controllers/Mail/Controller/mail.class.php");
            $config=getConfiguration("Compania");
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['controller']);
            unset($datos['submit']);
            $datos['referencia']=str_replace("Referencia: ","",$datos['referencia']);
            $empresa=queryBD("select nombre,email from empresas as e inner join inmuebles as i on i.id_empresa=e.id where referencia='".$datos['referencia']."'",$index->getDB());
            $mail=new Mail();
            $mail->setDestiny($empresa[0]["email"],$empresa[0]["nombre"]);
            $mail->setRemiter($datos["mail"],$datos['nombre']);
            $mail->setMessage($datos,"Más Info","mas-info");
            $correcto=$mail->send();
            if($correcto) header("Location: ".getUrl()."/envio-correcto.php");
            else header("Location: ".getUrl()."/envio-fallido.php");
            die();
        break;
    }
    if(!isset($_POST['ajax']) || !$_POST['ajax']) header("Location: ".$_SERVER['HTTP_REFERER']);
}
?>
