<?php
    class Agentes extends Base{
        const ID=2;
        const nombre="Agentes";
        private $empresas=null;
        function __construct($db){
            parent::__construct($db);
            $this->controllerAjax=array("insert"=>0,"delete"=>1,"delete_selected"=>2,"import"=>3,"new_field"=>4,"login"=>5,"modify"=>6,"getonexml"=>7);
            $this->table="agentes";
        }
        function getName(){
            return self::nombre;
        }
        function getID(){
            return self::ID;
        }
        function insertar($datos){
            if(numberOfRows("select * from agentes where nombre=\"". trim($datos['nombre']) ."\" and apellidos =\"".trim($datos['apellidos'])."\"" ,$this->db)!=0)
                return false;
            $imagen=  convertirAJPG($_FILES['foto']);
            $nombre=explode(".",$_FILES['foto']['name']);
            $nobre=$nombre[0];
            if(imagejpeg($imagen,getRoot()."images/agentes/$nombre.jpg")){
                $datos['foto']=getUrl()."/images/agentes/$nombre.jpg";
                parent::insertar($datos);
                return true;
            }
        }
        function modificar($datos){
            parent::modificar($datos);
            if(is_uploaded_file($_FILES['foto']['tmp_name'])){
                $imagen=  convertirAJPG($_FILES['foto']);
                $nombre=explode(".",$_FILES['foto']['name']);
                $nombre=$nombre[0];

                if(imagejpeg($imagen,getRoot()."images/agentes/$nombre.jpg")){
                    $datos['foto']=getUrl()."/images/agentes/$nombre.jpg";
                    return true;
                }
            }
        }
        function render(){
            include_once(getRoot()."controllers/Agentes/View/agentes.html.php");
        }
        function renderLogin(){
            include_once(getRoot()."controllers/Agentes/View/agentes_login.html.php");
        }
        public static function getForOptions($conexion){
            $opciones=array(array("valor"=>"-1","opcion"=>"-"));
            $valores=queryBD("select id as valor,concat(nombre,' ',apellidos) as opcion from agentes", $conexion);
            return array_merge($opciones,$valores);
        }
        function translateValues($key,$value){
            $return=$value;
            switch($key){
                case 'codigo_empresa':
                    if($this->empresas==null){
                        $this->empresas=new Empresas($this->db);
                        $this->empresas->cargar();
                    }
                    $empresa=$this->empresas->buscar('id', (int)$value);
                    $return=$empresa['nombre'];
                break;
            }
            return $return;
        }
    }
?>
