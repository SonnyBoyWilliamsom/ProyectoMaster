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
        if(isset($selectedForm['file'])){
            ob_start();
            $template=(string)$selectedForm['file'];
            include getRoot()."/controllers/Formularios/View/$template.html.php";
            $html=ob_get_clean();
            return array("datos"=>array("html"=>$html));
        }
        else{
            $datos=array("controller"=> $controller->getName() ,"subindex"=>(string)$selectedForm['subindex'],"lang"=>$lang,"name"=>(string)$selectedForm['name'],"submitText"=>(strlen((string)$selectedForm['submit-text'])>0)?(string)$selectedForm['submit-text']:"Aceptar","action"=>(isset($selectedForm['action']) && strlen((string)$selectedForm['action'])>0)?(string)$selectedForm['action']:((isInAdmin())?"/admin/":""));
            if(isset($selectedForm['events-id']) && strlen($selectedForm['events-id'])>0) $datos["eventos"]=self::obtenerEventos($controller,(int) $selectedForm['events-id']);
            if(isset($selectedForm['total-pages'])){
                /* Capturar paginado */
                $pagina=0;
                $fields=array();
                $datos['page']=(string)$selectedForm['page'];
                $datos['total-pages']=(string)$selectedForm['total-pages'];
                $datos['titles']=(string)$selectedForm['titles'];
                $formulario=array("datos"=>$datos);
                foreach($formsXML->form as $form){
                    if(strtolower($form["name"])==  strtolower($name) && ((int)$form["page"])==$pagina){
                        $formulario[]=self::procesarFields($form, $controller, $conexion);
                        $pagina++;
                    }
                    if($pagina==$datos['total-pages']) break;
                }
            }
            else{
                $fields=self::procesarFields($selectedForm,$controller,$conexion);
                $formulario=array("datos"=>$datos,$fields);
            }
        }
        return $formulario;
    }
    static function procesarFields($selectedForm,$controller,$conexion){
        $controllerName=$controller->getName();
        $i=0;
        $fields=array();
        foreach($selectedForm as $field){
            foreach($field->attributes() as $key=>$attribute){
                $fields[$i][$key]=(string)$attribute;
            }
            if(isset($field['events-id']) && strlen($field['events-id'])>0) $fields[$i]["eventos"]=self::obtenerEventos($controller,(int) $field['events-id']);
            if((string)$field['tipo']=="select") $fields[$i]['options']=self::obtenerOptions($controllerName, (string)$field['id-options'],$conexion);
            if((string)$field['tipo']=="slide" or (string)$field['tipo']=="double-slide") $fields[$i]['param']=self::obtenerSlides ($controllerName, (string)$field['key_bd'], $conexion);
            if((string)$field['tipo']=="autocomplete") $fields[$i]['source']=self::obtenerSource ($controllerName, (string)$field['key_bd'], $conexion);
            $i++;
        }
        return $fields;
    }
    static function obtenerSource($controllerName,$key,$conexion){
        $options=self::obtenerOptions($controllerName, $key, $conexion);
        $sources=array();
        if(count($options)>0)
            foreach($options as $option)
                $sources[]="{value:'$option[valor]',label:'$option[opcion]'}";
        return $sources;
    }
    static function obtenerSlides($controllerName,$key,$conexion){
        $datalist=null;
        $optionsXML= simplexml_load_file(getRoot()."controllers/".ucwords($controllerName)."/Resources/options.xml");
        foreach($optionsXML->select as $select){
            if(strtolower($select["name"])==strtolower($key)){
                $options=$select;
                break;
            }
        }
        return array("min"=>(string)$options["min"],"max"=>(string)$options['max'],"step"=>(string)$options["step"]);
    }
    static function obtenerEventos($controller,$id){
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
    static function obtenerOptions($controllerName,$key,$conexion){
        $options=null;
        $optionsXML= simplexml_load_file(getRoot()."controllers/".ucwords($controllerName)."/Resources/options.xml");
        foreach($optionsXML->select as $select){
            if(strtolower($select["name"])==strtolower($key)){
                $options=$select;
                break;
            }
        }
        if(!isset($options['ajax'])){
            if(isset($options['controller'])){
                $invocador=ucfirst((string)$options['controller']);
                $include=$options['controller'];
                if($include=="provincias" || $include=="poblaciones") $include="zonas";
                if(!in_array(strtolower(getRoot()."controllers/$include/controller/$include.class.php"),includedFiles()))
                    include_once getRoot()."controllers/".ucwords($include)."/Controller/$include.class.php";
                return $invocador::getForOptions($conexion);
            }
            else{
                $auxiliar=array();
                foreach($options as $option)
                    $auxiliar[]=array("valor"=>(string)$option['value'],"opcion"=>(string)$option,"selected"=>(string)$option['selected']);
                return $auxiliar;
            }
        }
    }
    static function printFormulario($campos,$db=null,$template="template01"){
        if(isset($campos['datos']['html'])){
            $form=$campos['datos']['html'];
        }
        else{
            $datos=$campos['datos'];
            unset($campos['datos']);
            $pagina=0;
            $paginas=(isset($datos['total-pages']))?(int)$datos['total-pages']:1;
            $plantilla=file_get_contents(getRoot()."/controllers/Formularios/View/$template.html");
            $porcion=array();
            $ocurrencias=substr_count($plantilla,'field');
            $eventos=(isset($datos['eventos']) && count($datos['eventos'])>0)?self::traducirEventos($datos['eventos']):"";
            $form="<form class=\"$template\" enctype=\"multipart/form-data\" method=\"post\" id=\"form_".$datos['controller']."_".$datos['name']."\" action=\"". getUrl().$datos['action']."\" $eventos >";
            if($paginas<=1){
                $nCampos=count($campos[$pagina]);
                for($i=0;$i<$nCampos;$i+=$ocurrencias){
                    for($j=0;$j<min($nCampos-$i,$ocurrencias);$j++){
                        $field=self::procesarTemplate($campos[$pagina][$i+$j],$datos);
                        $porcion[]=str_replace(array("[field_$j]","[type]"),array($field,$campos[$pagina][$i+$j]['tipo']), $plantilla);
                    }
                }
            }
            else{
                if(isset($datos['titles']) && strlen($datos['titles'])>0) $titles=explode(",",$datos['titles']);
                for($pagina=0;$pagina<$paginas;$pagina++){
                    $porcion[]= "<div class=\"paginado tres-columnas ".($pagina+1);

                    if($pagina==0) $porcion[]= " activo ";
                    $porcion[]= "\" >";
                    $porcion[]= "<div class=\"direccion\">";
                    if($pagina!=0) $porcion[]="<a href=\"javascript:void(0);\" class=\"anterior\" onclick=\"javascript:paginaAnterior(document.getElementById('form_".$datos['controller']."_".$datos['name']."'),$pagina);\"><span class=\"fa-angle-double-left\"></span>Página Anterior</a>";
                    if($pagina<($paginas-1)) $porcion[]="<a href=\"javascript:void(0);\" class=\"siguiente\" onclick=\"javascript:paginaSiguiente(document.getElementById('form_".$datos['controller']."_".$datos['name']."'),".($pagina+2).");\"></span>Página Siguiente<span class=\"fa-angle-double-right\"></a>";
                    $porcion[]="</div>";
                    if(isset($datos['titles']) && strlen($datos['titles'])>0) $porcion[]= "<p class=\"titulo-pagina\">".($pagina+1)."/".$paginas." ".$titles[$pagina]."</p>";
                    $porcion[]="<div class=\"campos\">";
                    $nCampos=count($campos[$pagina]);
                    for($i=0;$i<$nCampos;$i+=$ocurrencias){
                        $toReplace=array();
                        $replaceTo=array();
                        for($j=0;$j<$ocurrencias;$j++){
                            $field=(isset($campos[$pagina][$i+$j]))?self::procesarTemplate($campos[$pagina][$i+$j],$datos):"";
                            $toReplace[]="[field_$j]";
                            $toReplace[]="[type]";
                            $replaceTo[]=$field;
                            $replaceTo[]=$campos[$pagina][$i+$j]['tipo'];
                        }
                        $porcion[]=str_replace($toReplace, $replaceTo, $plantilla);
                    }
                    $porcion[]="</div>";
                    $porcion[]= "<div class=\"direccion\">";
                    if($pagina!=0) $porcion[]="<a href=\"javascript:void(0);\" class=\"anterior\" onclick=\"javascript:paginaAnterior(document.getElementById('form_".$datos['controller']."_".$datos['name']."'),$pagina);\"><span class=\"fa-angle-double-left\"></span>Página Anterior</a>";
                    if($pagina<($paginas-1)) $porcion[]="<a href=\"javascript:void(0);\" class=\"siguiente\" onclick=\"javascript:paginaSiguiente(document.getElementById('form_".$datos['controller']."_".$datos['name']."'),".($pagina+2).");\"></span>Página Siguiente<span class=\"fa-angle-double-right\"></a>";
                    $porcion[]="</div>";
                    $porcion[]= "</div>";
                }
            }
            $form.=implode("",$porcion);
            $form.= "<input type=\"hidden\" class=\"query\" name=\"query\" value=\"".$datos['subindex']."\" />";
            $form.= "<input type=\"hidden\" class=\"controller\" name=\"controller\" value=\"".$datos['controller']."\" />";
            $form.= "<input type=\"submit\" name=\"submit\" class=\"submit\" value=\"".$datos['submitText']."\" />";
            $form.= "</form>";
        }
        echo $form;
    }
    static function procesarChecked($selected){
        return (isset($selected) && $selected==true)?"true":"false";
    }
    static function procesarTemplate($field,$datos){
        $aux=file_get_contents(getRoot()."/controllers/Formularios/Resources/templates/".$field['tipo'].".html");
        $checked=(isset($field['selected']))?"checked=\"".self::procesarChecked($field['selected'])."\"":"";
        $value=(isset($field['value']))?$field['value']:"";
        $eventos=(isset($field['eventos']))?self::traducirEventos($field['eventos']):"";
        $dataValidation=(isset($field['data-validation']))?"data-validation=\"".$field['data-validation']."\"":"";
        $dataOptional=(isset($field['data-optional']))?"data-optional=\"".$field['data-optional']."\"":"";
        $toReplace=array("[form_name]","[checked]","[value]","[events]","[data-validation]","[data-optional]");
        $replaceTo=array($datos['controller']."_".$datos['name'],$checked,$value,$eventos,$dataValidation,$dataOptional);
        foreach($field as $key=>$value){
            if($key=="options") $value=self::optionsToHtml($field['options']);
            if($key=="source") $value=self::sourceToHtml($field['source']);
            if($key=="param"){
                list($min,$max,$step)=self::paramsToHtml($field['param']);
                $toReplace[]="[min]";
                $toReplace[]="[max]";
                $toReplace[]="[step]";
                $replaceTo[]=$min;
                $replaceTo[]=$max;
                $replaceTo[]=$step;
                continue;
            }
            if($key=="label_es") $key="label";
            $toReplace[]="[$key]";
            $replaceTo[]=$value;
        }
        return str_replace($toReplace,$replaceTo,$aux);
    }
    static function paramsToHtml($arrayParams){
        return array($arrayParams['min'],$arrayParams['max'],$arrayParams['step']);
    }
    static function optionsToHtml($arrayOptions){
        if(count($arrayOptions)==0) return "";
        $options=array();
        foreach($arrayOptions as $option){
            $selected=(isset($option['selected']) && strlen($option['selected'])>0)?"selected":"";
            $options[]="<option value=\"".$option['valor']."\" $selected>".$option['opcion']."</option>";
        }
        return implode("",$options);
    }
    static function sourceToHtml($arraySource){
        return "[".implode(",",$arraySource)."]";
    }
    static function printFormularioPaginado($campos,$pagina,$paginas,$template="paginado",$db=null){
        $datos=$campos['datos'];
        unset($campos['datos']);
        include getRoot()."/controllers/Formularios/View/$template.html.php";
    }
}
?>
