<?php
    class Empresas extends Base{
        const ID=1;
        const nombre="Empresas";

        function __construct($db){
            parent::__construct($db);
            $this->controllerAjax=array("insert"=>0,"delete"=>1,"delete_selected"=>2,"import"=>3,"new_field"=>4,"login"=>5,"modify"=>6,"getonexml"=>7,"mes"=>8);
            $this->table="empresas";
        }
        function getName(){
            return self::nombre;
        }
        function getID(){
            return self::ID;
        }
        function insertar($datos){
            if(numberOfRows("select * from empresas where nombre=\"". trim($datos['nombre']) ."\" and apellidos =\"".trim($datos['apellidos'])."\"" ,$this->db)!=0)
                return false;
            $imagen=  convertirAJPG($_FILES['foto']);
            $nombre=explode(".",$_FILES['foto']['name']);
            $nobre=$nombre[0];
            if(imagejpeg($imagen,getRoot()."images/empresas/$nombre.jpg")){
                $datos['foto']=getUrl()."/images/empresas/$nombre.jpg";
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

                if(imagejpeg($imagen,getRoot()."images/empresas/$nombre.jpg")){
                    $datos['foto']=getUrl()."/images/empresas/$nombre.jpg";
                    return true;
                }
            }
        }
        function render(){
            include_once(getRoot()."controllers/Empresas/View/empresas.html.php");
        }
        static function getAsociadoMes($db){
            $empresaArray=queryBD("select * from empresas where destacado=1",$db);
            $empresa=$empresaArray[0];
            return $empresa['codigo_empresa'];
        }
        static function renderDestacado($codigo){
            include_once(getRoot()."controllers/Empresas/View/$codigo.html.php");
        }
        public static function getForOptions($conexion){
            $opciones=array(array("valor"=>"-1","opcion"=>"-"));
            $valores=queryBD("select id as valor,nombre as opcion from empresas", $conexion);
            return array_merge($opciones,$valores);
        }
        static function getLogo($id,$db){
            $logo=queryBD("select logo from empresas where id=$id",$db);
            return $logo[0]['logo'];
        }
        function translateValues($key,$value){ }
    }
?>
