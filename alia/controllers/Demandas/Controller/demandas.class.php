<?php
class Demandas extends Base{
    const ID=4;
    const nombre="demandas";
    private $clientes=null;
    private $zonas=null;
    private $poblaciones=null;
    private $provincias=null;
    private $tipos=null;

    public function __construct($db) {
        parent::__construct($db);
        $this->controllerAjax=array("recuperar"=>1,"insertar"=>2,"delete"=>3,"delete_selected"=>4,"modify"=>5,"getonexml"=>6,"getonexmlfromclient"=>7,"presupuesto"=>8);
        $this->table="demandas";
    }
    function getName(){
        return self::nombre;
    }
    function getID(){
        return self::ID;
    }
    function cargar(){
        $this->registros=queryBD("select * from $this->table order by fecha_creacion desc",$this->db);
    }
    public function insertarStatic($datos, $conexion = false) {
        if(!$conexion) $conexion=connectBD();
        foreach($datos as $key=>$dato){
            $keys[]=$key;
            $values[]="\"".(($dato=="on")?true:$dato)."\"";
            if($key=="fecha_creacion") continue;
            $condiciones[]="$key=\"$dato\"";
        }
        if(numberOfRows("select * from demandas where ". implode(" and ",$condiciones) ,$conexion)!=0)
            return false;
        $query="insert into demandas(".implode(",",$keys).") values(".implode(",",$values).")";
        queryBD($query,$conexion);
        return true;
    }

    public function nuevoCampo($campo, $conexion = false) {
        include_once("formularios.class.php");
        Formularios::nuevoCampo($campo,self::nombre,self::ID,$conexion);
    }
    function render($render="demandas"){
        include_once(getRoot()."controllers/Demandas/View/$render.html.php");
    }
    function renderViewTemplate(){
        include_once(getRoot()."controllers/Demandas/View/datos.html.php");
    }
    function buscadorAvanzado($datos){
        $procesado=false;
        $where=array();
        $sql="select * from $this->table";
        foreach($datos as $key=>$value){
            if($value==-1) continue;
            switch($key){
                case 'anno':
                    if(!$procesado){
                        $mes=(isset($datos['mes']) && $datos['mes']!=-1)?$datos['mes']:"%";
                        $anno=$datos['anno'];
                        $where[]="fecha_creacion like \"$anno-$mes-%\"";
                        $procesado=true;
                    }
                break;
                case 'mes':
                    if(!$procesado){
                        $mes=$datos['mes'];
                        $anno=(isset($datos['anno']) && $datos['anno']!=-1)?$datos['anno']:"%";
                        $where[]="fecha_creacion like \"$anno-$mes-%\"";
                        $procesado=true;
                    }
                break;
                case 'presupuesto':
                    $where[]=$key." <= ".$value;
                break;
                case 'activa':
                    if($value=="true") $where[]=$key."=".$value;
                break;
                default:
                    $where[]=$key."=".$value;
                break;
            }

        }
        if(count($where)>0) $sql.=" where ". implode(" AND ",$where);
        return queryBD($sql ,$this->db);
    }
    function buscarClientesDemandas($datos){}
    function translateValues($key,$value){
        $return=$value;
        switch($key){
            case 'fecha_creacion':
                $return=formatDate($value);
            break;
            case 'id':
                $return="D";
                for($i=4-strlen($value);$i>0;$i--) $return.="0";
                    $return.=$value;
            break;
            case 'id_cliente':
                if($this->clientes==null){
                    $this->clientes=new Clientes($this->db);
                    $this->clientes->cargar();
                }
                $cliente=$this->clientes->buscar('id', (int)$value);
                $codigo=($cliente['primera_importacion'])?$cliente['codigo_primera_importacion']:($cliente['id']+5000);
                if($cliente['arrendatario']) $codigo.="A";
                if($cliente['comprador']) $codigo.="C";
                if($cliente['inquilino']) $codigo.="I";
                if($cliente['vendedor']) $codigo.="V";
                $return=$codigo;
            break;
            case 'presupuesto':
                $return=number_format((int)$value,0,',','.')." €";
            break;
            case 'c_provincia':
                if($this->provincias==null){
                    $this->provincias=new Provincias($this->db);
                    $this->provincias->cargar();
                }
                $provincia=$this->provincias->buscar('id', (int)$value);
                $return=$provincia['nombre'];
            break;
            case 'c_poblacion':
                if($this->poblaciones==null){
                    $this->poblaciones=new Poblaciones($this->db);
                    $this->poblaciones->cargar();
                }
                $poblacion=$this->poblaciones->buscar('id', (int)$value);
                $return=$poblacion['nombre'];
            break;
            case 'c_zona':
                if($this->zonas==null){
                    $this->zonas=new Zonas($this->db);
                    $this->zonas->cargar();
                }
                $zona=$this->zonas->buscar('id', (int)$value);
                $return=$zona['nombre'];
            break;
            case 'c_tipo':
                if((int)$value==-1) $return="Ind.";
                else{
                    if($this->tipos==null){
                        $this->tipos=new Tipos($this->db);
                        $this->tipos->cargar();
                    }
                    $tipo=$this->tipos->buscar('id', (int)$value);
                    $return=$tipo['nombre'];
                }
            break;
            case 'piscina':
            case 'trastero':
            case 'garaje':
            case 'ascensor':
                if($value==-1) $return="Ind.";
                else $return=($value==0)?"Si":"No";
            break;
            case 'dormitorios':
                if($value==-1) $return="Ind.";
                else if($value==4) $return="4+";
                else $return=$value;
            break;
            case 'tipo':
                if($value==2) $return="Alquiler";
                else if($value==1) $return="Compra";
                else $return="Sin definir";
            break;
            case 'activa':
            if($value==0) $return="No";
            else $return="Si";
            break;
        }
        return $return;
    }
    function replaceInList($line,$id){
        $codigo=$this->translateValues("id_cliente",$id);
        return str_replace(array("[id_cliente]"),array($codigo),$line);
    }
}
