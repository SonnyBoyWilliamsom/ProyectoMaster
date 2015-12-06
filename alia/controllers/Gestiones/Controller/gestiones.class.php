<?php
class Gestiones extends Base{
    const ID=10;
    const nombre="gestiones";

    function __construct($db){
        parent::__construct($db);
        $this->controllerAjax=array("insert"=>0,"delete"=>1,"delete_selected"=>2,"import"=>3,"new_field"=>4,"modify"=>5,"getonexml"=>6);
        $this->table="gestiones";
    }
    function getName(){
        return self::nombre;
    }
    function getID(){
        return self::ID;	
    }
    function insertar($datos){
        if(numberOfRows("select * from $this->table where nombre=\"". trim($datos['nombre']) ."\"" ,$this->db)!=0)
            return false;
        parent::insertar($datos);
    }
    function render(){
        include_once(getRoot()."controllers/Gestiones/View/gestiones.html.php");
    }
    public static function getForOptions($conexion){
        $opciones=array(array("valor"=>"-1","opcion"=>"-"));
        $valores=queryBD("select id as valor,nombre as opcion from gestiones", $conexion);
        return array_merge($opciones,$valores);
    }
    function translateValues($key,$value){}
}