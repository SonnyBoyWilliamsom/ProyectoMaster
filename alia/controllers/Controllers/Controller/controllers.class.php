<?php
class Controllers extends Base{

    const ID=6;
    const nombre="controllers";

    public function __construct($db) {
        parent::__construct($db);
        $this->controllerAjax=array("insert"=>0,"delete"=>1,"delete_selected"=>2,"import"=>3,"new_field"=>4);
        $this->table="controllers";

    }

    public function getName() {
        return self::nombre;
    }
    public function getID() {
        return self::ID;
    }
    public function render(){
        include_once(getRoot()."Controllers/Controllers/View/controllers.html.php");
    }
    public static function getControllerParams($id,$conexion=false){
        $resultado=queryBD("select * from controllers where id=$id", $conexion);
        return $resultado[0];
    }
    public static function getForOptions($conexion){
        $opciones=array(array("valor"=>"-1","opcion"=>""));
        $valores=queryBD("select id as valor,nombre as opcion from controllers", $conexion);
        return array_merge($opciones,$valores);
    }
    function translateValues($key,$value){

    }
}
