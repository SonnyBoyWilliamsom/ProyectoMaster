<?php
include_once(__DIR__ . "/../../../processing/library.php");
class Tareas extends Base {

    const ID = 14;
    const nombre = "Tareas";
    function __construct($db){
    parent::__construct($db);
        $this->controllerAjax = array("insert" => 0, "delete" => 1, "delete_selected" => 2, "import" => 3, "new_field" => 4, "modify" => 5, "getonexml" => 6, "pictures" => 7, "getpictures" => 8, "modifypictures" => 9,"search"=>10);
        $this->table = "tareas";
    }
    function getName() {
        return self::nombre;
    }

    function getID() {
        return self::ID;
    }

    function insertar($datos) {
        $columnas=$this->getDbTable("inmuebles");
        $keys=array();
        $values=array();
        if(isset($columnas) && count($columnas)>0){
            foreach($columnas as $columna){
                if(isset($_POST[$columna['name']]) && strlen($_POST[$columna['name']])>0){
                    $keys[]=$columna['name'];
                    $values[]=($columna['type']=="varchar" || $columna['type']=="date")?"\"".$_POST[$columna['name']]."\"":$_POST[$columna['name']];
                }
            }
            queryBD("insert into inmuebles (".implode(",",$keys).") values(".implode(",",$values).")",$this->db);
        }
    }

    function eliminar($identificador) {
        queryBD("delete from $this->table where c_inmuebles=$identificador", $this->db);
    }

    function eliminarVarios($identificadores) {
        queryBD("delete from $this->table where c_inmuebles in (" . implode(",", $identificadores) . ")", $this->db);
    }

    function render($render = "tareas") {
        include_once(getRoot() . "controllers/Tareas/View/$render.html.php");
    }
    function translateValues($key,$value){}
}

?>
