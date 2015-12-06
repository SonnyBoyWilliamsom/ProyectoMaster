<?php
if(isset($_POST['query'])){
    $selector=0+$_POST['query'];
    switch($selector){
        case 0://Modificar Campo
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['controller']);
            unset($datos['ajax']);
            unset($datos['submit']);
            echo Formularios::modificarCampo($datos,$db);
        break;
        case 1://Añadir Campo
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['ajax']);
            unset($datos['submit']);
            $controller=queryBD("select controller,subindex from relacion_form_cont where id_form=".$datos['id'],$db);
            $datos['controller']=$controller[0]['controller'];
            Formularios::nuevoCampo($datos,$db);
        break;
        case 2:
            $id=$_POST['id'];
            Formularios::eliminarCampo($id,$db);
            echo "0";
        break;
    }
    if(!isset($_POST['ajax']) || !$_POST['ajax']) header("Location: ".$_SERVER['HTTP_REFERER']);
}
?>