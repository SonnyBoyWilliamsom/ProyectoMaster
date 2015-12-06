<?php
    if(!defined(__DIR__)){
        define('__DIR__', dirname(__FILE__));
    }
    if(!isset($_GET['controller'])) $controllerName="inmuebles";
    else $controllerName=$_GET['controller'];
    include_once __DIR__."/../processing/library.php";
    include_once __DIR__."/../controllers/Base/Controller/base.class.php";
    include_once __DIR__."/../controllers/Controllers/Controller/controllers.class.php";
    include_once __DIR__."/../controllers/Idiomas/Controller/idiomas.class.php";
    $db=connectBD();
    if(strtolower($controllerName)!="login" && strtolower($controllerName)!="logout"){
        $controllers=new Controllers($db);
        $controllers->cargar();
        if(!isset($_POST['query'])){
            header('Content-Type: text/html; charset=utf-8');
            include_once __DIR__."/../processing/session.php";
            $controllerParams=$controllers->buscar("nombre", ucfirst($controllerName));
            if(count($controllerParams)!=0){
                include_once getRoot().$controllerParams['directorio'].$controllerParams['fichero'];
                $controller=new $controllerParams['nombre']($db);
                if(!isset($_GET['render'])) $controller->render();
                else $controller->render($_GET['render']);
            }
            else{
                include_once(__DIR__."/../404.php");
            }
        }
        else{
            $controllerName=strtolower($_POST['controller']);
            $controllerParams=$controllers->buscar("nombre", ucfirst($controllerName));
            include_once getRoot().$controllerParams['directorio'].$controllerParams['fichero'];
            include_once getRoot().$controllerParams['directorio'].(strtolower($controllerParams['nombre']))."Ajax.class.php";
        }
    }
    else if(strtolower($controllerName)=="login"){
        include_once __DIR__."/../controllers/Agentes/Controller/agentes.class.php";
        $agentes=new Agentes($db);
        $agentes->renderLogin();
    }
    else if(strtolower($controllerName)=="logout"){
        session_start();
        session_destroy();
        header("Location: ../admin/login");
    }
    else{
        include_once(__DIR__."/../404.php");
    }
    disconnectBD($db);
?>
