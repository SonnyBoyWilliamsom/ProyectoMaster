<?php
class Idiomas{
    private $idiomas;
    private $db=null;
    private $activos;

    function Idiomas($db,$activos=true){
        $this->idiomas=array();
        $this->db=$db;
        $this->activos=$activos;
    }
    function cargar(){
        $query="select * from idiomas";
        if($this->activos==true) $query.=" where activo=" . $this->activos;
        $this->idiomas=queryBD($query,$this->db);                    
    }
    function obtenerIdiomas(){
        return $this->idiomas;
    }
    function obtenerIso(){
        $iso=array();
        foreach($this->idiomas as $idiomas)
            $iso[]=$idiomas['codigo_iso639'];
        return $iso;
    }
    function obtenerNombresIdiomas(){
        $iso=array();
        foreach($this->idiomas as $idiomas)
            $iso[]=$idiomas['idioma'];
        return $iso;
    }
    function desactivarIdioma($id){
        queryBD("update idiomas set activo=false where id=$id",$this->db);
        $this->idiomas($this->db,$this->activos);
    }
    function activarIdioma($id){
        queryBD("update idiomas set activo=true where id=$id",$this->db);
        $this->idiomas($this->db,$this->activos);
    }
    static function instalarIdioma($db,$datos){

    }
}
?>