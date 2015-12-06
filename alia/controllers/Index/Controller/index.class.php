<?php
include_once(__DIR__ . "/../../../processing/library.php");

class Index extends Base {

    const ID = 12;
    const nombre = "Index";

    function __construct($db) {
        parent::__construct($db);
        $this->controllerAjax = array("call" => 0);
        $this->table = "";
    }

    function getName() {
        return self::nombre;
    }

    function getID() {
        return self::ID;
    }

    function insertar($datos) {
        return true;
    }

    function eliminar($identificador) {
        return true;
    }

    function eliminarVarios($identificadores) {
        return true;
    }

    function render($render = "index") {
        echo $this->renderMeta($render,self::nombre);
        include_once(getRoot() . "controllers/Index/View/$render.html.php");
    }

    function modificar($datos) {
        return true;
    }
    function translateValues($key,$value){}
}
?>
