<?php
class Formularios {
    static function getFormularioFromXML($controller,$name,$lang,$conexion){
        $i=0;
        $controllerName=$controller->getName();
        if($controllerName=="provincias" || $controllerName=="poblaciones") $controllerName="Zonas";
        $formsXML= simplexml_load_file(getRoot()."controllers/".ucwords($controllerName)."/Resources/form.xml");
        $optionsXML=null;
        $selectedForm=null;
        foreach($formsXML->form as $form){
            if(strtolower($form["name"])==  strtolower($name)){
                $selectedForm=$form;
                break;
            }
        }
        $formulario["datos"]=array("controller"=> $controller->getName() ,"subindex"=>(string)$selectedForm['subindex'],"lang"=>$lang,"name"=>(string)$selectedForm['name']);
        if(isset($selectedForm['events-id']) && strlen($selectedForm['events-id'])>0) $formulario["datos"]["eventos"]=Formularios::obtenerEventos($controllerName,(int) $selectedForm['events-id']);
        foreach($selectedForm as $field){
            $formulario[$i]=array("key_bd"=>(string)$field['key_bd'],"tipo"=>(string)$field['tipo'],"obligatorio"=>(string)$field['obligatorio'],"longitud"=>(string)$field['longitud'],"orden"=>$i,"label_es"=>(string)$field['label_es'],"value"=>(string)$field['value'],"selected"=>(string)$field['selected']);
            if(isset($field['events-id']) && strlen($field['events-id'])>0) $formulario[$i]["eventos"]=Formularios::obtenerEventos($controllerName,(int) $field['events-id']);
            /*Selects*/
            if((string)$field['tipo']=="select"){
                $options=null;
                $keyBD=(string)$field['key_bd'];
                if($optionsXML==null) $optionsXML= simplexml_load_file(getRoot()."controllers/".ucwords($controllerName)."/Resources/options.xml");
                foreach($optionsXML->select as $select){
                    if(strtolower($select["name"])==strtolower($keyBD)){
                        $options=$select;
                        break;
                    }                        
                }
                if(!isset($options['ajax'])){
                    if(isset($options['controller'])){
                        $invocador=ucfirst((string)$options['controller']);
                        $include=$options['controller'];
                        if($include=="provincias" || $include=="poblaciones") $include="zonas";
                        if(!in_array(strtolower(getRoot()."controllers/$include/controller/$include.class.php"),includedFiles())){
                            include_once getRoot()."controllers/".ucwords($include)."/Controller/$include.class.php";
                        }
                        $formulario[$i]['opciones']=$invocador::getForOptions($conexion);
                    }
                    else{
                        foreach($options as $option){
                            $formulario[$i]['opciones'][]=array("valor"=>(string)$option['value'],"opcion"=>(string)$option,"selected"=>(string)$option['selected']);
                        }
                    }
                }
            }
            if((string)$field['tipo']=="slide"){
                $datalist=null;
                $keyBD=(string)$field['key_bd'];
                if($optionsXML==null) $optionsXML= simplexml_load_file(getRoot()."controllers/".ucwords($controllerName)."/Resources/options.xml");
                foreach($optionsXML->select as $select){
                    if(strtolower($select["name"])==strtolower($keyBD)){
                        $options=$select;
                        break;
                    }                        
                }
                $formulario[$i]['param']=array("min"=>(string)$options["min"],"max"=>(string)$options['max'],"step"=>(string)$options["step"]);
            }
            /*/Selects*/
            $i++;
        }
        return $formulario;
    }
    static function obtenerEventos($controllerName,$id){
        $eventsXML= simplexml_load_file(getRoot()."controllers/".ucwords($controllerName)."/Resources/events.xml");
        $events=array();
        foreach($eventsXML->events as $event){
            if(strtolower($event["id"])==  strtolower($id)){
                $selectedEvents=$event;
                foreach($selectedEvents->children() as $eventName=>$listener)
                    $events[]=$eventName."=\"".$listener."\"";
                break;
            }
        }
        return $events;
    }
    static function printFormulario($campos,$template="template01",$db=null){
        $datos=$campos['datos'];
        unset($campos['datos']);
        include getRoot()."/controllers/Formularios/View/$template.html.php";
    }
    static function printFormularioPaginado($campos,$pagina,$paginas,$template="paginado",$db=null){
        $datos=$campos['datos'];
        unset($campos['datos']);
        include getRoot()."/controllers/Formularios/View/$template.html.php";
    }
}
?>