<?php
if(isset($_POST['query'])){
    include_once(__DIR__."/../../../processing/library.php");
    $selector=0+$_POST['query'];
    switch($selector){
        case 0:
            $db=connectBD();
            $datos=array('archivo'=>$_FILES['idioma']['tmp'],'codigo'=>$_POST['codigo'],'idioma'=>$_POST['idioma']);
            Idiomas::instalarIdioma($db,$_FILE['idioma']);
            disconnectBD($db);
        break;	
    }
    if(!isset($_POST['ajax']) || !$_POST['ajax']) header("Location: ".$_SERVER['HTTP_REFERER']);
}
?>