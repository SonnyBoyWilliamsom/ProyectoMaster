<?php
class Formularios {
        private $db=null;
        private $registros=null;
        private $controllerAjax=array("modify"=>0,"insert"=>1,"delete"=>2);
        const ID=0;
        const nombre="formularios";

        function Formularios($db){
            $this->db=$db;
            $this->registros=array();
        }
        function getName(){
            return self::nombre;	
        }
        function getID(){
            return self::ID;
        }
        function cargar(){
            $this->registros=queryBD("select * from formularios where tipo<>\"hidden\" order by id_formulario",$this->db);
        }
        function obtenerRegistros(){
            return $this->registros;
        }
        function ids_formularios(){
            return queryBD("select id_formulario from formularios group by id_formulario",$this->db);
        }
        function getTipos(){
            return queryBD("select * from options where id_campo=11",$this->db);
        }
        function getAjaxIndex($function){
            return ($this->controllerAjax[$function]);
        }
        static function getControllerName($idForm,$conexion){
            $aux=queryBD("select nombre from relacion_form_cont as a left join controllers as b on a.controller=b.id where a.id_form=$idForm limit 1",$conexion);
            return ucwords($aux[0]['nombre']);
        }
        static function eliminarCampo($id,$conexion){
            queryBD("delete from formularios where id_campo=$id",$conexion);

        }
        static function nuevoCampo($campo,$conexion=false){
            if(!$conexion) $conexion=connectBD();
            require_once(getRoot()."controllers/Controllers/Controller/controllers.class.php");
            $nombreControlador=  Controllers::getControllerParams($campo['controller'], $conexion);
            $idiomas=new Idiomas($conexion);
            $idiomas->cargar();
            $idiomas=$idiomas->obtenerIso();
            foreach($idiomas as $idioma){
                    $condiciones[]="label_$idioma=\"".$campo["label_$idioma"]."\"";
                    $labels[]="label_$idioma";
                    $values[]="\"". ucwords($campo["label_$idioma"]) ."\"";
            }
            if(count($condiciones)>1) $condicion=implode(" and ",$condiciones);
            else $condicion=$condiciones[0];
            if(numberOfRows("select * from formularios where id_formulario=".$campo['id']." and $condicion" ,$conexion)==0){
                    $keyBd=deleteSpecialChars($campo['label_es']);
                    queryBD("insert into formularios(id_formulario,key_bd,tipo,longitud,obligatorio,". implode(",",$labels) .") values(".$campo['id'].",\"$keyBd\",\"".$campo['tipo']."\",".$campo['longitud'].",".$campo['obligatorio'].",". implode(",",$values) .")",$conexion);
                    if(!in_array($keyBd,nameOfColumns($nombreControlador['nombre'],$conexion))) queryBD("alter table ".$nombreControlador['nombre']." add column $keyBd varchar(".$campo['longitud'].")",$conexion);
                    return 0;
            }
            else return -1;
        }

        function getFormularioCampos($controller){
            $campos=queryBD("select * from formularios where id_formulario=".self::ID,$this->db);
            $datos=queryBD("select controller,subindex from relacion_form_cont where id_form=".self::ID,$this->db);
            $controllerParams=  Controllers::getControllerParams($campos['controller'], $conexion);
            $formulario["datos"]=array("controller"=>$controller->getName(),"subindex"=>$controller->getAjaxIndex("new_field"),"lang"=>"es","page"=>$controllerParams['fichero']);
            $idiomas=new Idiomas($this->db);
            $idiomas->cargar();
            $codigos=$idiomas->obtenerIso();
            foreach($campos as $campo){
                    $campoFormulario=array("key_bd"=>$campo['key_bd'],"tipo"=>$campo['tipo'],"obligatorio"=>$campo['obligatorio']);
                    foreach($codigos as $codigo)
                            $campoFormulario["label_$codigo"]=$campo["label_$codigo"];
                    if($campo['tipo']=="select"){
                            $opciones=queryBD("select valor,opcion from options where id_campo=".$campo['id_campo'],$this->db);	
                            $campoFormulario['opciones']=$opciones;
                    }
                    $formulario[]=$campoFormulario;
            }
            return $formulario;
        }
        static function getFormulario($lang,$id,$conexion=false){
            include_once(getRoot()."controllers/Controllers/Controller/controllers.class.php");
            $campos=queryBD("select * from formularios where id_formulario=$id order by orden asc",$conexion);
            $datos=queryBD("select controller,subindex,name from relacion_form_cont where id_form=$id",$conexion);
            $controllerParams=  Controllers::getControllerParams($datos[0]['controller'], $conexion);
            $formulario["datos"]=array("controller"=> $controllerParams['nombre'] ,"subindex"=>$datos[0]['subindex'],"lang"=>$lang,"page"=>$controllerParams['fichero'],"name"=>$datos[0]['name']);
            $idiomas=new Idiomas($conexion);
            $idiomas->cargar();
            $codigos=$idiomas->obtenerIso();
            foreach($campos as $campo){
                    $campoFormulario=array("id_campo"=>$campo['id_campo'],"key_bd"=>$campo['key_bd'],"tipo"=>$campo['tipo'],"obligatorio"=>$campo['obligatorio'],"longitud"=>$campo['longitud'],"orden"=>$campo['orden']);
                    foreach($codigos as $codigo)
                            $campoFormulario["label_$codigo"]=$campo["label_$codigo"];
                    if($campo['tipo']=="select"){
                        $opciones=queryBD("select valor,opcion,controller_id from options where id_campo=".$campo['id_campo'],$conexion);
                        if(count($opciones)>1 && $opciones[0]['controller_id']==-1) $campoFormulario['opciones']=$opciones;
                        else if(count($opciones)==1 && $opciones[0]['controller_id']!=-1){
                            $optionControllerParams=Controllers::getControllerParams($opciones[0]['controller_id'],$conexion);
                            if($opciones[0]['controller_id']!=-0)
                                include_once(getRoot().$optionControllerParams['directorio'].$optionControllerParams['fichero']);
                            $string=ucwords($optionControllerParams['nombre']);
                            $campoFormulario['opciones']=$string::getForOptions($conexion);
                        }
                    }
                    $formulario[]=$campoFormulario;
            }
            return $formulario;
        }
        static function getFormularioFromXML($controller,$name,$lang){
            $controllerName=$controller->getName();
            if($controllerName=="provincias" || $controllerName=="poblaciones") $controllerName="Zonas";
            $formsXML= simplexml_load_file(getRoot()."controllers/$controllerName/Resources/form.xml");
            $optionsXML=null;
            $selectedForm=null;
            foreach($formsXML->form as $form){
                if(strtolower($form["name"])==  strtolower($name)){
                    $selectedForm=$form;
                    break;
                }
            }
            $formulario["datos"]=array("controller"=> $controller->getName() ,"subindex"=>(string)$selectedForm['subindex'],"lang"=>$lang,"name"=>(string)$selectedForm['name']);
            $i=0;
            foreach($selectedForm as $field){
                $formulario[$i]=array("key_bd"=>(string)$field['key_bd'],"tipo"=>(string)$field['tipo'],"obligatorio"=>(string)$field['obligatorio'],"longitud"=>(string)$field['longitud'],"orden"=>$i,"label_es"=>(string)$field['label_es']);
                /*Selects*/
                if((string)$field['tipo']=="select"){
                    $options=null;
                    $keyBD=(string)$field['key_bd'];
                    if($optionsXML==null) $optionsXML= simplexml_load_file(getRoot()."controllers/$controllerName/Resources/options.xml");
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
                            if($include=="provincias" || $include=="poblaciones") $include="Zonas";
                            include_once getRoot()."controllers/$include/Controller/$include.class.php";
                            $formulario[$i]['opciones']=$invocador::getForOptions(connectBD());
                        }
                        else{
                            foreach($options as $option){
                                $formulario[$i]['opciones'][]=array("valor"=>(string)$option['valor'],"opcion"=>(string)$option);
                            }
                        }
                    }
                }
                /*/Selects*/
                $i++;
            }
            return $formulario;
        }
        static function modificarCampo($datos,$db){
            $condiciones=array();
            $aBuscar=array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó','Ú');
            $reemplazo=array('&aacute;', '&eacute;', '&iacute;', '&oacute;', '&uacute;', '&Aacute;', '&Eacute;', '&Iacute;', '&Oacute;','&Uacute;');
            foreach($datos as $llave=>$valor){
                if($llave=="id_campo") $clavePrimaria="$llave=$valor";
                else if($llave=="obligatorio") $condiciones[]="$llave=$valor";
                else $condiciones[]="$llave=\"".str_replace($aBuscar,$reemplazo,$valor)."\"";
            }
            $condiciones=implode(" and ",$condiciones);
            $aux=queryBD("select * from formularios where $condiciones and $clavePrimaria",$db);
            if(count($aux)!=0) return false;
            else{
                $condiciones=str_replace(" and ",",",$condiciones);
                queryBD("update formularios set $condiciones where $clavePrimaria",$db);
                return true;
            }
        }
        static function printFormulario($campos){
            $datos=$campos['datos'];
            unset($campos['datos']);
            echo "<form method=\"post\" id=\"".$datos['controller']."\" action=\"http://". getUrl()."/admin/\">";
            foreach($campos as $campo){
                if($campo['tipo']!="hidden" && $campo['tipo']!="oculto") echo "<label>".$campo['label_'.$datos['lang']]."</label>";
                switch($campo['tipo']){
                    case "select":
                        echo "<select type=\"".$campo['tipo']."\" name=\"".$campo['key_bd']."\" id=\"".$campo['key_bd']."\"";
                        if($campo['obligatorio']) echo "class=\"required\" ";
                        echo">";
                        if(isset($campo['opciones'])){
                            foreach($campo['opciones'] as $opcion){
                                echo "<option value=\"".$opcion['valor']."\">".$opcion['opcion']."</option>";	
                            }
                        }
                        echo "</select>";				
                    break;
                    case "textarea":
                        echo "<textarea name=\"".$campo['key_bd']."\" id=\"".$campo['key_bd']."\" placeholder=\"". strip_tags($campo['label_'.$datos['lang']]) ."\"></textarea>";
                    break;
                    case "hidden":
                        echo "<input type=\"".$campo['tipo']."\" name=\"".$campo['key_bd']."\" id=\"".$campo['key_bd']."\">";
                    break;
                    case "oculto": break;
                    default:
                        echo "<input type=\"".$campo['tipo']."\" name=\"".$campo['key_bd']."\" id=\"".$campo['key_bd']."\"  placeholder=\"".strip_tags($campo['label_'.$datos['lang']])."\" ";
                        if($campo['obligatorio']) echo "class=\"required\" ";
                        echo">";
                    break;	
                }
            }
            echo "<input type=\"hidden\" name=\"query\" value=\"".$datos['subindex']."\" />";
            echo "<input type=\"hidden\" name=\"controller\" value=\"".$datos['controller']."\" />";
            echo "<input type=\"submit\" name=\"submit\" class=\"submit\" value=\"Aceptar\" />";
            echo "</form>";
        }
        public static function getForOptions($conexion){
            require_once(getRoot()."controllers/Controllers/Controller/controllers.class.php");
            $aux=queryBD("select id_campo,label_es,id_formulario from formularios", $conexion);

            $opciones=array();
            foreach($aux as $campo){
                $paramCont=  Formularios::getControllerName($campo['id_formulario'], $conexion);
                $opciones[]=array("valor"=>$campo['id_campo'],"opcion"=>$paramCont.".".$campo['label_es']);
            }
            return $opciones;
        }
        public function render(){
            include_once(getRoot()."Controllers/Formularios/View/formularios.html.php");
        }
    }
?>