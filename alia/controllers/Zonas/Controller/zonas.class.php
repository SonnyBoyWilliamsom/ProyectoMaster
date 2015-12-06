<?php
    class Zonas extends Base{
        private $codigo_sup;
        const ID=9;
        const nombre="zonas";

        public function __construct($db,$activa=false) {
            parent::__construct($db);
            $this->controllerAjax=array("insert"=>16,"delete"=>17,"delete_selected"=>18,"import"=>19,"new_field"=>20,"modify"=>21,"getonexml"=>22);
            $this->table="zonas";
            $this->codigo_sup=-1;
        }
        function cargar(){
            $this->registros=queryBD("select * from $this->table",$this->db);
        }
        public function establecerCodigoPoblacion($codigo_sup){
            $this->codigo_sup=$codigo_sup;
        }
        public function getName() {
            return self::nombre;
        }

        public function getID() {
            return self::ID;
        }

        public function insertar($datos, $conexion = false) {
           if(numberOfRows("select * from $this->table where nombre=\"". trim($datos['nombre']) ."\" or id =\"".trim($datos['id'])."\"" ,$this->db)!=0)
                return false;
           parent::insertar($datos);
        }
        public static function getForOptions($conexion,$codigo_sup=-1){
            $opciones=array(array("valor"=>"-1","opcion"=>"Todas"));
            if($codigo_sup!=-1) $valores=queryBD("select id as valor,nombre as opcion from zonas where codigo_po=$codigo_sup", $conexion);
            else $valores=queryBD("select id as valor,nombre as opcion from zonas", $conexion);
            return array_merge($opciones,$valores);
        }
        function render(){
            include_once(getRoot()."controllers/Zonas/View/zonas.html.php");
        }
        function translateValues($key,$value){
            $return=$value;
            switch($key){
                case 'codigo_po':
                    if($this->poblacionSup==null){
                        $poblaciones=new Poblaciones($this->db);
                        $poblaciones->cargar();
                        $poblacion=$poblaciones->buscar('id',(int)$value);
                        $this->poblacionSup=$poblacion['nombre'];
                        $return=$poblacion['nombre'];
                    }
                    else $return = $this->poblacionSup;
                break;
            }
            return $return;
        }
        function replaceInList($line,$id){
            if(count($this->registros)==0) $this->cargar();
            $zona=$this->buscar("id",$id);
            return str_replace("[id]",$zona['id'],$line);
        }
    }
