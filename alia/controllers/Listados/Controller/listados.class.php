<?php
class Listados {
    static function getListadosFromXML($controller,$name,$lang,$conexion){
        $i=0;
        $controllerName=$controller->getName();
        if($controllerName=="provincias" || $controllerName=="poblaciones") $controllerName="Zonas";
        $listsXML= simplexml_load_file(getRoot()."controllers/".ucwords($controllerName)."/Resources/list.xml");
        $selectedList=null;
        foreach($listsXML->list as $list){
            if(strtolower($list["name"])==  strtolower($name)){
                $selectedList=$list;
                break;
            }
        }
        $lista=array("datos"=>array("controllerName"=>$controllerName,"id"=>(string)$list['id']),"cells"=>self::procesarFields($selectedList, $controllerName, $conexion));
        return $lista;
    }
    static function procesarFields($selectedList,$controllerName,$conexion){
        $cells=0;
        $arrayCells=array();
        foreach($selectedList as $cell){
            $fields=0;
            foreach($cell->attributes() as $key=>$attribute)
                $arrayCells[$cells]['data'][$key]=(string)$attribute;
            foreach($cell as $field){
                foreach($field->attributes() as $key=>$attribute)
                    $arrayCells[$cells][$fields][$key]=(string)$attribute;
                $fields++;
            }
            $cells++;
        }
        return $arrayCells;
    }
    static function printListado($lista,$controller,$template="template01",$registrosIn=null){
        $porciones=array();
        $registros=($registrosIn==null)?$controller->obtenerRegistros():$registrosIn;
        $datos=$lista["datos"];
        $cells=$lista["cells"];
        include_once(getRoot()."/controllers/Listados/View/$template.html.php");
        $porciones[]=$start;
        $row="";
        foreach($cells as $cell){
            $celda="";
            if(isset($cell['data']['events-id']) && strlen($cell['data']['events-id'])>0){
                $eventos=self::obtenerEventos($controller, $cell['data']['events-id']);
                $celda.= "<a href=\"javascript:void(0);\" title=\"".$cell['data']['label_es']."\" " . self::traducirEventos($eventos).">";
            }
            $celda.= $cell['data']['label_es'];
            if(isset($cell['data']['events-id']) && strlen($cell['data']['events-id'])>0) $celda.= "</a>";
            $row.=str_replace("[header]",$celda,$headerTag);
        }
        $porciones[]=str_replace("[row]",$row,$headerRowTag);
        foreach($registros as $registro){
            $row="";
            foreach($cells as $cell){
                $celda="";
                $cellContent=$cell;
                unset($cellContent['data']);
                for($i=0;$i<count($cellContent);$i++){
                    if(count($cellContent)>1){
                        if($i==0) $celda.="<ul>";
                        $celda.= "<li>";
                    }
                    if(isset($cellContent[$i]['key_bd']) && strlen($cellContent[$i]['key_bd'])>0){
                        $valor=(isset($cellContent[$i]['preprocessing']) && (bool)$cellContent[$i]['preprocessing'])?$controller->translateValues($cellContent[$i]['key_bd'],$registro[$cellContent[$i]['key_bd']]):$registro[$cellContent[$i]['key_bd']];
                        $celda.=$valor;
                    }
                    else if(isset($cellContent[$i]['delete-selected']) && strlen($cellContent[$i]['delete-selected'])>0) $celda.="<input type=\"checkbox\" class=\"". $registro[$datos['id']] ."\">";
                    else{
                        $target=(isset($cellContent[$i]['target']))?$cellContent[$i]['target']:"_blank";
                        $celda.= "<a href=\"".self::traducirHref($cellContent[$i]['href'],$controller,$registro[$datos['id']])."\" ";
                        if(isset($cellContent[$i]['events-id']))
                        $celda.=" ".self::traducirEventos(self::obtenerEventos($controller,$cellContent[$i]['events-id'],$registro[$datos['id']]));
                        $celda.=" target=\"$target\" >".$cellContent[$i]['content']."</a>";
                    }
                    if(count($cellContent)>1){
                        $celda.="</li>";
                        if($i==count($cellContent)-1) $celda.="</ul>";
                    }
                }
                $row.=str_replace("[cell]",$celda,$cellTag);
            }
            $porciones[]= str_replace(array("[row]","[id]"),array($row,$registro[$datos['id']]),$rowTag);
        }
        $porciones[]= $end;
        echo implode("",$porciones);
    }
    static function obtenerEventos($controller,$id,$idReg=""){
        $eventsXML= simplexml_load_file(getRoot()."controllers/".ucwords($controller->getName())."/Resources/events.xml");
        $events=array();
        $toReplace=array("[controllerName]","[id]");
        $replaceTo=array($controller->getName(),$idReg);
        foreach($eventsXML->events as $event){
            if(strtolower($event["id"])==strtolower($id)){
                $selectedEvents=$event;
                foreach($selectedEvents->children() as $eventName=>$listener){
                    preg_match_all("/ajax-index:(\w*)/",$listener,$auxiliar);
                    for($i=0;$i<count($auxiliar[1]);$i++){
                        $toReplace[]="[".$auxiliar[0][$i]."]";
                        $replaceTo[]=$controller->getAjaxIndex($auxiliar[1][$i])+0;
                    }
                    $events[]=str_replace($toReplace,$replaceTo,$eventName."=\"".$listener."\"");
                }
                break;
            }
        }
        return $events;
    }
    static function traducirEventos($eventos){
        return implode(" ",$eventos);
    }
    static function traducirHref($href,$controller,$idReg=""){
        if(strlen($href)>0){
            $toReplace=array("[controllerName]","[id]","[base]","[url]");
            $replaceTo=array($controller->getName(),$idReg,getUrl(),"http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
            $linea=str_replace($toReplace,$replaceTo,$href);
            $linea=$controller->replaceInList($linea,$idReg);
            return $linea;
        }
        else return "javascript:void(0);";
    }
}
?>
