<?php
include_once(__DIR__ . "/../../../processing/library.php");
class Captacion extends Base {

    const ID = 13;
    const nombre = "Captacion";
    private $agentes=null;
    function __construct($db) {
        parent::__construct($db);
        $this->controllerAjax = array("insert" => 0, "delete" => 1, "delete_selected" => 2, "import" => 3, "new_field" => 4, "modify" => 5, "getonexml" => 6, "pictures" => 7, "getpictures" => 8, "modifypictures" => 9,"search"=>10);
        $this->table = "captacion";
    }

    function getName() {
        return self::nombre;
    }

    function getID() {
        return self::ID;
    }

    function insertar($datos) {
        $columnas=$this->getDbTable("captacion");
        $keys=array();
        $values=array();
        if(isset($columnas) && count($columnas)>0){
            foreach($columnas as $columna){
                if(isset($_POST[$columna['name']]) && strlen($_POST[$columna['name']])>0){
                    $keys[]=$columna['name'];
                    $values[]=($columna['type']=="varchar" || $columna['type']=="date")?"\"".$_POST[$columna['name']]."\"":$_POST[$columna['name']];
                }
            }
            queryBD("insert into $this->table (".implode(",",$keys).") values(".implode(",",$values).")",$this->db);
        }
    }

    function eliminar($identificador) {
        queryBD("delete from $this->table where id=$identificador", $this->db);
    }

    function eliminarVarios($identificadores) {
        queryBD("delete from $this->table where id in (" . implode(",", $identificadores) . ")", $this->db);
    }

    function render($render = "captacion") {
        $head=$this->renderMeta($render,self::nombre);
        switch($render){
            case "ficha":
                $head=$this->headFicha($head);
            break;
        }
        echo $head;
        include_once(getRoot() . "controllers/Captacion/View/$render.html.php");
    }
    function modificar($datos) {
        $columnas=$this->getDbTable("captacion");
        $update=array();
        if(isset($columnas) && count($columnas)>0){
            foreach($columnas as $columna){
                if ($columna['name'] == "id") {
                    $id=$_POST['id'];
                    continue;
                }
                if(isset($_POST[$columna['name']]) && strlen($_POST[$columna['name']])>0){
                    $key=$columna['name'];
                    $value=($columna['type']=="varchar" || $columna['type']=="date")?"\"".$_POST[$columna['name']]."\"":$_POST[$columna['name']];
                    $update[]=$key."=".$value;
                }
            }
            queryBD("update $this->table set " . implode(", ", $update) . " where id=$id", $this->db);
        }
    }

    function translateValues($key,$value){
        $return=$value;
        switch($key){
            case 'f_entrada':
            case 'f_ultimo_contacto':
                $return=formatDate($value);
            break;
            case 'agente_captador':
                if($this->agentes==null){
                    $this->agentes=new Agentes($this->db);
                    $this->agentes->cargar();
                }
                $agente=$this->agentes->buscar('id',(int)$value);
                $return=$agente['nombre']." ".$agente['apellidos'];
            break;
        }
        return $return;
    }
}

?>
