<?php
class Options extends Base{
    const ID=5;
    const nombre="options";
    
    public function __construct($db) {
        parent::__construct($db);
        $this->controllerAjax=array("insert"=>0,"delete"=>1,"delete_selected"=>2);
        $this->table="options";
        
    }
    public function getName() {
        return self::nombre;
    }

    public function getID() {
        return self::ID;
    }

    public function insertar($datos) {
        if(numberOfRows("select * from options where id_campo=\"". trim($datos['id_campo']) ."\" and opcion =\"".trim($datos['opcion'])."\"" ,$this->db)!=0)
                return false;
        foreach($datos as $key=>$dato){
                $keys[]=$key;
                $values[]="\"".(($dato=="on")?true:$dato)."\"";
        }
        $query="insert into options(".implode(",",$keys).") values(".implode(",",$values).")";
        queryBD($query,$this->db);
        return true;
    }

    public function render(){
        include_once(getRoot()."Controllers/Options/View/options.html.php");
    }
    function translateValues($key,$value){}
}