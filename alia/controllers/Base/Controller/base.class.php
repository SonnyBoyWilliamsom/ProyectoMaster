<?php
abstract class Base{
    protected $registros;
    protected $db=null;
    protected $table;
    protected $controllerAjax;

    abstract function getName();
    abstract function getID();
    abstract function render();
    abstract function translateValues($key,$value);

    function __construct($db){
        $this->registros=array();
        $this->db=$db;
    }
    function getDB(){
        return $this->db;
    }
    function getTable(){
        return $this->table;
    }
    function cargar(){
        $this->registros=queryBD("select * from $this->table",$this->db);
    }
    function buscadorAvanzado($datos){
        $sql="select * from $this->table";
        $where=array();
        foreach($datos as $key=>$value)
            if($value!=-1)
                $where[]=$key."=".$value;
        if(count($where)>0) $sql.=" where ". implode(" AND ",$where);
        return queryBD($sql ,$this->db);
    }
    function obtenerRegistros(){
        return $this->registros;
    }
    function buscar($campo,$valor){
        foreach($this->registros as $registro){
            if($registro[$campo]==$valor)
                return $registro;
        }
    }
    function getAjaxIndex($function){
        return ($this->controllerAjax[$function]);
    }
    function getFormulario($name,$lang="es"){
        include_once(getRoot()."controllers/Formularios/Controller/formularios.class.php");
        return Formularios::getFormularioFromXML($this,$name,"es",$this->db);
    }
    function getLista($name,$lang="es"){
        include_once(getRoot()."controllers/Listados/Controller/listados.class.php");
        return Listados::getListadosFromXML($this,$name,"es",$this->db);
    }
    function insertar($datos){
        foreach($datos as $key=>$dato){
            $keys[]=$key;
            $values[]="\"".(($dato=="on")?true:$dato)."\"";
        }
        $query="insert into $this->table(".implode(",",$keys).") values(".implode(",",$values).")";
        queryBD($query,$this->db);
        return true;
    }
    function modificar($datos){
        foreach($datos as $key=>$dato){
            if($key=="id"){
                $id=$dato;
                continue;
            }
            $update[]="$key=\"$dato\"";
        }
        $query="update $this->table set ". implode(", ",$update) ." where id=$id";
        queryBD($query,$this->db);
        return true;
    }
    function eliminar($identificador){
        queryBD("delete from $this->table where id=$identificador",$this->db);
    }
    function eliminarVarios($identificadores){
        queryBD("delete from $this->table where id in (". implode(",",$identificadores) .")",$this->db);
    }
    function renderXML(){
        header('Content-Type: text/xml; charset=utf-8');
        header('Content-Disposition: attachment; filename="'. $this->getName() .'.xml"');
        $xml="<$this->getName()>";
        foreach($this->registros as $reg){
            $xml.="<". substr($controller,0,-1) .">";
            foreach($reg as $key=>$value){
                $xml.= "<$key><![CDATA[". utf8_encode($value)."]]></$key>";
            }
            $xml.= "</". substr($controller,0,-1) .">";
        }
        $xml.="</$this->getName()>";
        echo $xml;
    }
    function renderMeta($render,$controller){
        $fichero=(isInAdmin())?"base_admin":"base";
        ob_start();
        include getRoot()."/controllers/Base/View/$fichero.html.php";
        $html=ob_get_clean();
        $reemplazo=array("title"=>"Alcorcón Alia - ". ucwords($render),"meta"=>"");
        if(file_exists(getRoot()."controllers/".ucwords($controller)."/Resources/meta.xml")){
            $metaXML= simplexml_load_file(getRoot()."controllers/".ucwords($controller)."/Resources/meta.xml");
            foreach($metaXML->metas as $metas){
                if(strtolower($metas["render"])==  strtolower($render)){
                    $selectedMetas=$metas;
                    break;
                }
            }
            if(isset($selectedMetas->title) && strlen((string)$selectedMetas->title)>0) $reemplazo['title']=(string)$selectedMetas->title;
            if(isset($selectedMetas->meta) && count($selectedMetas->meta)>0)
                foreach($selectedMetas->meta as $meta){
                    if(isset($meta['name']) && strlen((string)$meta['name'])) $reemplazo['meta'].="<meta name=\"$meta[name]\" content=\"$meta[content]\">\n";
                    if(isset($meta['property']) && strlen((string)$meta['property'])) $reemplazo['meta'].="<meta property=\"$meta[property]\" content=\"$meta[content]\">\n";
                }
        }
        $toReplace=array("[meta]","[title]");
        $replaceTo=array($reemplazo['meta'],$reemplazo['title']);
        foreach($_GET as $key=>$value){
            if($key=="render" || $key=="controller") continue;
            $toReplace[]="[$key]";
            $replaceTo[]=ucwords($value);
        }
        return str_replace($toReplace,$replaceTo,$html);
    }
    function getDbTable($tableName){
        if(file_exists(getRoot()."controllers/".$this->getName()."/Resources/database.xml")){
            $tableXML= simplexml_load_file(getRoot()."controllers/".$this->getName()."/Resources/database.xml");
            $columns=array();
            foreach($tableXML as $table){
                if((string)$table['name']==$tableName){
                    foreach($table as $column)
                        $columns[]=array("name"=>(string)$column['name'],"type"=>(string)$column['type']);
                    break;
                }
            }
            return $columns;
        }
        return null;
    }
    function uploadFiles($ruta,$documentsKey){
        $paths=array();
        if(!file_exists(getRoot().$ruta)){
            mkdir(getRoot().$ruta);
        }
        if(!file_exists(getRoot().$ruta.'/'.$_POST[$documentsKey])){
            mkdir(getRoot().$ruta.'/'.$_POST[$documentsKey]);
        }
        foreach($_FILES as $clave=>$file){
            $i=count($paths);
            $paths[$i]['name']=str_replace(array("//","#","-"),array("/","",""),trim($ruta.'/'.$_POST[$documentsKey].'/'.$clave.'-'.$file['name'],"/"));
            $paths[$i]['id']=$clave;
            move_uploaded_file($file['tmp_name'],getRoot().$paths[$i]['name']);
        }
        return $paths;
    }
}
?>
