<?php
include_once(dirname(__FILE__)."/data/const.php");
class Noticias{
    private $db;
    function __construct($db){
        $this->db=$db;
    }
    function exec(){
        $xml=file_get_contents(URL);
        $xmlParser=simplexml_load_string($xml);
        $entradas=$xmlParser->entry;
        $i=0;
        foreach($xmlParser->entry as $entrada){
            $titulo=(string)$entrada->title;
            $auxiliar=explode(" ",substr(strip_tags((string)$entrada->content),0,LONG));
            unset($auxiliar[count($auxiliar)-1]);
            $contenido=trim(implode(" ",$auxiliar));
            $enlace=(string)$entrada->link[4]['href'];
            $this->procesarPlantilla(array("titulo"=>$titulo,"contenido"=>$contenido,"enlace"=>$enlace));
            $i++;
            if($i>=CANTIDADNOTICIAS) break;
        }
    }
    function procesarPlantilla($data){
        $template=file_get_contents(dirname(__FILE__)."/templates/template.html.php");
        echo str_replace(array("[titulo]","[contenido]","[enlace]"),$data,$template);
    }
}
