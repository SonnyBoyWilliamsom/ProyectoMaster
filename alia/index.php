<?php
    if(!defined(__DIR__)){
        define('__DIR__', dirname(__FILE__));
    }
    if (!isset($_GET['controller'])){
        $controllerName = "index";
        $controllerRender="index";
    }
    else{
        $controllerName = $_GET['controller'];
        $controllerRender = $_GET['render'];
    }
    include_once __DIR__ . "/processing/library.php";
    include_once __DIR__ . "/controllers/Base/Controller/base.class.php";
    include_once __DIR__ . "/controllers/Controllers/Controller/controllers.class.php";
    include_once __DIR__ . "/controllers/Idiomas/Controller/idiomas.class.php";
    $db = connectBD();
    $controllers = new Controllers($db);
    $controllers->cargar();
    if (!isset($_POST['query']) || (int)$_POST['query']==-1){
        $controllerParams = $controllers->buscar("nombre", ucfirst($controllerName));
        session_start();
        if(!isset($_SESSION)) session_destroy();
        header('Content-Type: text/html; charset=utf-8');
        if (count($controllerParams) != 0) {
            include_once getRoot() . $controllerParams['directorio'] . $controllerParams['fichero'];
            $controller = new $controllerParams['nombre']($db);
            $controller->render($controllerRender);
        }
        else {
            include_once(__DIR__ . "/404.php");
        }
    }
    else{
        $controllerName=strtolower($_POST['controller']);
        $controllerParams=$controllers->buscar("nombre", ucfirst($controllerName));
        include_once getRoot().$controllerParams['directorio'].$controllerParams['fichero'];
        include_once getRoot().$controllerParams['directorio'].(strtolower($controllerParams['nombre']))."Ajax.class.php";
    }
    disconnectBD($db);
?>
