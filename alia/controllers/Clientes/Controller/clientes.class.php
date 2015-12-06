<?php
    class Clientes extends Base{
        const ID=1;
        const nombre="clientes";
        private $agentes=null;
        function __construct($db){
            parent::__construct($db);
            $this->controllerAjax=array("insert"=>0,"delete"=>1,"delete_selected"=>2,"import"=>3,"new_field"=>4,"modify"=>5,"getonexml"=>6);
            $this->table="clientes";
        }
        function getName(){
            return self::nombre;
        }
        function getID(){
            return self::ID;
        }
        function cargar(){
            $this->registros=queryBD("select * from $this->table order by fecha_entrada desc,id desc",$this->db);
        }
        function insertar($datos){
            session_start();
            if(numberOfRows("select * from $this->table  where (nombre=\"". trim($datos['nombre']) ."\" and apellidos =\"".trim($datos['apellidos'])."\") or mail=\"".$datos['mail']."\"" ,$this->db)!=0)
                return false;
            $datos['fecha_entrada']=date('Y-m-d');
            $datos['id_agente_entrada']=$_SESSION['usuario']['id'];
            parent::insertar($datos);
            $this->mailing($datos);
        }
        function render(){
            include_once(getRoot()."controllers/Clientes/View/clientes.html.php");
        }
        function mailing($datos){
            include_once(getRoot()."controllers/Demandas/Controller/demandas.class.php");
            $demandas=new Demandas($this->db);
            $demandas->cargar();
            $demandas->buscarClientesDemandas($datos);
        }
        function buscadorAvanzado($datos){
            $sql="select * from $this->table";
            foreach($datos as $key=>$value){
                if($value!=-1 && $value!="false"){
                    if($value=="true" || $value=="false") $value=($value=="true")?true:false;
                    $value=is_string($value)?"\"$value\"":$value+0;
                    $where[]=$key."=$value";
                }
            }
            if(count($where)>0) $sql.=" where ". implode(" AND ",$where);
            return queryBD($sql ,$this->db);
        }
        public static function getForOptions($conexion,$codigo_sup=-1){
            $opciones=array(array("valor"=>"-1","opcion"=>""));
            $valores=queryBD("select id as valor,concat(nombre,' ',apellidos) as opcion from clientes order by nombre", $conexion);
            return array_merge($opciones,$valores);
        }
        public static function getForOptionsFichaInmueble($conexion,$codigo_sup=-1){
            $opciones=array(array("valor"=>"-1","opcion"=>""));
            $valores=queryBD("select id as valor,concat(nombre,' ',apellidos) as opcion from clientes where vendedor=true or arrendatario=true order by nombre", $conexion);
            return array_merge($opciones,$valores);
        }
        function translateValues($key,$value){
            $return=$value;
            switch($key){
                case 'id':
                    $cliente=$this->buscar('id', (int)$value);
                    $codigo=($cliente['primera_importacion'])?$cliente['codigo_primera_importacion']:($cliente['id']+5000);
                    if($cliente['arrendatario']) $codigo.="A";
                    if($cliente['comprador']) $codigo.="C";
                    if($cliente['inquilino']) $codigo.="I";
                    if($cliente['vendedor']) $codigo.="V";
                    $return=$codigo;
                break;
                case 'fecha_entrada':
                    list($anno,$mes,$dia) = explode("-",$value);
                    $return="$dia/$mes/$anno";
                break;
                case 'c_zona':
                    if(isset($value) && $value!=null){
                        include_once(getRoot()."controllers/Zonas/View/Zonas.class.php");
                        $zonas=new Zonas($this->db);
                        $zonas->cargar();
                        $zonaInteres=$zonas->buscar("id",$value);
                        $return=$zonaInteres['nombre_admin'];
                    }
                break;
                case 'id_agente':
                case 'id_agente_entrada':
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
