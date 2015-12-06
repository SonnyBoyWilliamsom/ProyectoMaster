<?php
    class Tipos extends Base{
    const ID=11;
    const nombre="tipos";

        public function __construct($db,$activa=false) {
            parent::__construct($db);
            $this->controllerAjax=array("insert"=>0,"delete"=>1,"delete_selected"=>2,"modify"=>3,"getonexml"=>4);
            $this->table="tipos";
            $this->formularios=array("Nuevo"=>11);
       }
        public function getName() {
            return self::nombre;
        }
        public function getID() {
            return self::ID;
        }

        public function insertar($datos, $conexion = false) {
            if(numberOfRows("select * from $this->table  where nombre=\"". trim($datos['nombre']) ."\"",$this->db)!=0)
                return false;
            parent::insertar($datos);
        }
        public function render(){
            include_once(getRoot()."controllers/Tipos/View/tipos.html.php");
        }
        public static function getForOptions($conexion){
            $opciones=array(array("valor"=>"-1","opcion"=>"Todos"));
            $valores=queryBD("select id as valor,nombre as opcion from tipos", $conexion);
            return array_merge($opciones,$valores);
        }
        function translateValues($key,$value){}
}

?>
