<?php
include_once(dirname(__FILE__) . "/../../../processing/library.php");
class Inmuebles extends Base {

    const ID = 3;
    const nombre = "Inmuebles";
    const ruta = "docs/Inmuebles/";
    const documentsKey="referencia";
    private $zonas=null;
    private $poblaciones=null;
    private $tipos=null;
    private $gestiones=null;
    private $empresas=null;

    function __construct($db) {
        parent::__construct($db);
        $this->controllerAjax = array("insert" => 0, "delete" => 1, "delete_selected" => 2, "import" => 3, "new_field" => 4, "modify" => 5, "getonexml" => 6, "pictures" => 7, "getpictures" => 8, "modifypictures" => 9,"search"=>10,"compare"=>11,"orderPrice"=>12,"orderZone"=>13,"orderEstado"=>15);
        $this->table = "inmuebles";
    }
    function cargar(){
        $this->registros=queryBD("select * from inmuebles where activo_01=1 order by c_inmuebles desc",$this->db);
    }
    function cargarPanel($su=false,$codigoEmpresa=0){
        if($su) $this->registros=queryBD("select i.* from inmuebles as i inner join agentes as a on a.id=i.id_agente left join empresas as e on i.id_empresa=e.id  order by i.c_inmuebles desc",$this->db);
        else $this->registros=queryBD("select i.* from inmuebles as i inner join agentes as a on a.id=i.id_agente left join empresas as e on i.id_empresa=e.id where e.id=$codigoEmpresa  order by i.c_inmuebles desc",$this->db);
    }
    function cargarHistorico(){
        $this->registros=queryBD("select * from inmuebles where estado_gestion=2 order by fecha_venta desc",$this->db);
    }
    function getName() {
        return self::nombre;
    }

    function getID() {
        return self::ID;
    }

    function insertar($datos) {
        $columnas=$this->getDbTable("inmuebles");
        $keys=array('fecha_registro');
        $values=array("now()");
        if(isset($columnas) && count($columnas)>0){
            foreach($columnas as $columna){
                if(isset($_POST[$columna['name']]) && strlen($_POST[$columna['name']])>0){
                    $keys[]=$columna['name'];
                    if($columna['type']=="varchar"){
                        $values[]="\"".$_POST[$columna['name']]."\"";
                    }
                    elseif($columna['type']=="date"){
                        $auxiliar=explode("/",$_POST[$columna['name']]);
                        $fecha=$auxiliar[2]."-".$auxiliar[1]."-".$auxiliar[0];
                        $values[]="\"$fecha\"";
                    }
                    else{
                        $values[]=$_POST[$columna['name']];
                    }
                }
            }
            queryBD("insert into inmuebles (".implode(",",$keys).") values(".implode(",",$values).")",$this->db);
            $this->enviarEmail($datos);
        }
    }

    function eliminar($identificador) {
        queryBD("delete from $this->table where c_inmuebles=$identificador", $this->db);
    }

    function eliminarVarios($identificadores) {
        queryBD("delete from $this->table where c_inmuebles in (" . implode(",", $identificadores) . ")", $this->db);
    }

    function render($render = "inmuebles") {
        $head=$this->renderMeta($render,self::nombre);
        switch($render){
            case "ficha":
                $head=$this->headFicha($head);
            break;
        }
        echo $head;
        include_once(getRoot() . "controllers/Inmuebles/View/$render.html.php");
    }
    static function getSiguienteReferencia($db){
        $datos= queryBD("select referencia from inmuebles order by c_inmuebles desc", $db);
        $match=preg_replace("/[a-zA-Z]/","",$datos[0]['referencia']);
        $referencia=max(3000,$match+1);
        return $referencia;
    }
    private function headFicha($head){
        $this->cargar();
        $inmueble=$this->buscar("referencia", $_GET['referencia']);
        $campos=$this->getDbTable("inmuebles");
        $fotoPcpal=$this->getFotoPrincipal($inmueble['c_inmuebles'],$this->db);
        $etiquetas=array("[imagen_pcpal]");
        $valores=array($fotoPcpal['url']);
        foreach($campos as $campo){
            $etiquetas[]="[".$campo["name"]."]";
            $valores[]=$inmueble[$campo["name"]];
        }
        return str_replace($etiquetas, $valores, $head);
    }
    function modificar($datos) {
        $columnas=$this->getDbTable("inmuebles");
        $update=array();
        if(isset($columnas) && count($columnas)>0){
            foreach($columnas as $columna){
                if ($columna['name'] == "c_inmuebles") {
                    $id=$_POST['c_inmuebles'];
                    continue;
                }
                if(isset($_POST[$columna['name']]) && strlen($_POST[$columna['name']])>0){
                    $key=$columna['name'];
                    $value=($columna['type']=="varchar" || $columna['type']=="date")?"\"".$_POST[$columna['name']]."\"":$_POST[$columna['name']];
                    $update[]=$key."=".$value;
                }
                else if($columna['type']=="tinyint"){
                    $update[]=$columna['name']."=0";
                }
            }
            queryBD("update $this->table set " . implode(", ", $update) . " where c_inmuebles=$id", $this->db);
            /*if(isset($_FILES) && count($_FILES)>0){
                $archivos=$this->uploadFiles(self::ruta,self::documentsKey);
                for($i=0;$i<count($archivos);$i++){
                    $archivo=$archivos[$i];
                    $existe=queryBD("select * from inmuebles_documentos where c_inmuebles=$id and identificador='".$archivo['id']."'",$this->db);
                    if(count($existe)>0) queryBD("update inmuebles_documentos set url='".$archivo['name']."' where c_inmuebles=$id and identificador='".$archivo['id']."'",$this->db);
                    else queryBD("insert into inmuebles_documentos(c_inmuebles,activo_01,url,identificador) values($id,true,'".$archivo['name']."','".$archivo['id']."')",$this->db);
                }
            }*/
        }
    }

    function traducirValor($campo, $valor){
        $key=$campo['key_bd'];
        $retorno=$valor;
        switch($key){
            case 'accesible_01':
            case 'amueblado_01':
            case 'garaje_01':
            case 'aire_acondicionado_01':
            case 'piscina_01':
            case 'jardin_01':
            case 'trastero_01':
            case 'ascensor_01':
            case 'terraza_01':
            case 'vpo_01':
            case 'armarios_empotrados_01':
            case 'tendedero_01':
            case 'portero_01':
            case 'urbanizacion_01':
                if($valor!=-1){
                    $retorno=($valor==1)?"Sí":"No";
                }
            break;
            case 'n_banos':
            case 'n_aseos':
                if($valor==0) $retorno=-1;
                elseif($valor==4) $retorno="$valor+";
            break;
            case 'estado_vivienda':
                if($valor!=-1){
                    $estados=array(1=>"Nuevo",2=>"Buen Estado",3=>"Obra Nueva",4=>"A Reformar",5=>"Reformado");
                    $retorno=$estados[$valor];
                }
            break;
            case 'antiguedad':
                if($valor!=-1){
                    $antiguedades=array(1=>"1 a 5",2=>"5 a 20",3=>"20 a 40",4=>"+40");
                    $retorno=$antiguedades[$valor]." años";
                }
            break;
            case 'orientacion_solar':
                if($valor!=-1){
                    $orientacion=array(0=>"Norte",1=>"Sur",2=>"Este",3=>"Oeste",4=>"Noreste",5=>"Noroeste",6=>"Sureste",7=>"Suroeste");
                    $retorno=$orientacion[$valor];
                }
            break;
            case 'suelos':
                if($valor!=-1){
                    $suelos=array(0=>"Cerámico/Gres",1=>"Parque",2=>"Tarima",3=>"Otros");
                    $retorno=$suelos[$valor];
                }
            break;
            case 'calefaccion':
                if($valor!=-1){
                    $calefacciones=array(0=>"Sin Calefacción",1=>"Central",2=>"Eléctrica",3=>"Central",4=>"Gas",5=>"Gasoil",6=>"Otros");
                    $retorno=$calefacciones[$valor];
                }

            break;

        }
        return strtolower($retorno);
    }

    function guardarFotos($datos, $imagenes, $cInmueble) {
        $this->cargar();
        $inmueble=$this->buscar("c_inmuebles", $cInmueble);
        $referencia=$inmueble['referencia'];
        if(!file_exists(getRoot() . "/images/inmuebles/".$referencia)){
            mkdir(getRoot() . "/images/inmuebles/".$referencia,0777, true);
            chmod(getRoot() . "/images/inmuebles/".$referencia, 0777);
        }
        for ($i = 0; $i < 7; $i++) {
            if (!isset($imagenes[$i]) || strlen($imagenes[$i]) == 0)
                continue;
            $nombre = "imagen_".$referencia."_".$i;
            if(file_put_contents(getRoot() . "images/inmuebles/$referencia/$nombre.jpg",base64_decode(str_replace("data:image/jpeg;base64,","", $imagenes[$i])))){
                $ruta = getUrl() . "/images/inmuebles/$referencia/$nombre.jpg";
                $principal = isset($datos['principal'][$i]) + 0;
                $activa = ($datos['activa'][$i] == "on");
                $descripcion = $datos['descripcion'][$i];
                queryBD("insert into inmuebles_fotos(c_inmuebles,activo_01,url,principal,descripcion_corta) values($cInmueble,$activa,'$ruta',$principal,'$descripcion')", $this->db);
            }
        }
        //ejecutarHooks("updatePictures",$this->db,array("cinmueble"=>$cInmueble,"table"=>"inmuebles_fotos"));
    }
    static function getTextoReclamo($id,$db){
        $aux=queryBD("select texto_reclamo from inmuebles where c_inmuebles=$id", $db);
        return $aux[0]['texto_reclamo'];
    }
    function getFotos($id,$limite=-1) {
        $idAux=(int)$id;
        if($limite<=0) return queryBD("select * from inmuebles_fotos where c_inmuebles=$idAux", $this->db);
        else return queryBD("select * from inmuebles_fotos where c_inmuebles=$idAux limit $limite", $this->db);
    }
    static function getFotoPrincipal($id,$db) {
        $idAux=(int)$id;
        $aux=queryBD("select * from inmuebles_fotos where c_inmuebles=$idAux and principal=true", $db);
        if(isset($aux[0])) return $aux[0];
        return null;
    }
    function modificarFotos($datos, $imagenes) {
        $this->cargar();
        $inmueble=$this->buscar("c_inmuebles", $datos['cinmueble']);
        $referencia=$inmueble['referencia'];
        if(!file_exists(getRoot() . "/images/inmuebles/".$referencia)) mkdir(getRoot() . "/images/inmuebles/".$referencia);
        for ($i = 0; $i < 7; $i++) {
            if (!isset($datos['cfoto'][$i]) || strlen($datos['cfoto'][$i]) == 0) {
                if (!isset($imagenes[$i]) || strlen($imagenes[$i]) == 0)
                    continue;
                $nombre = "imagen_".$referencia."_".$i;
                if(file_put_contents(getRoot() . "images/inmuebles/$referencia/$nombre.jpg",base64_decode(str_replace("data:image/jpeg;base64,","", $imagenes[$i])))){
                    $ruta = getUrl() . "/images/inmuebles/$referencia/$nombre.jpg";
                    $principal = isset($datos['principal'][$i]) + 0;
                    $activa = ($datos['activa'][$i] == "on");
                    $descripcion = $datos['descripcion'][$i];
                    queryBD("insert into inmuebles_fotos(c_inmuebles,activo_01,url,principal,descripcion_corta) values(" . $datos['cinmueble'] . ",$activa,'$ruta',$principal,'$descripcion')", $this->db);
                }
            } else if (isset($datos['cfoto'][$i]) && $datos['cfoto'][$i] != 0 && $datos['descartada'][$i] == "1") {
                $this->eliminarFoto($datos['cfoto'][$i]);
            } else {
                if (isset($imagenes[$i]) && strlen($imagenes[$i]) != 0) {
                    $nombre = "imagen_".$referencia."_".$i;
                    if (file_put_contents(getRoot() . "images/inmuebles/$referencia/$nombre.jpg",base64_decode(str_replace("data:image/jpeg;base64,","", $imagenes[$i])))) {
                        $ruta = getUrl() . "/images/inmuebles/$referencia/$nombre.jpg";
                        queryBD("update inmuebles_fotos set marca=false,redimensionada=false,url='$ruta' where c_fotos=" . $datos['cfoto'][$i], $this->db);
                    }
                }
                $principal = isset($datos['principal'][$i]) + 0;
                $activa = isset($datos['activa'][$i]) + 0;
                $descripcion = $datos['descripcion'][$i];
                queryBD("update inmuebles_fotos set activo_01=$activa,principal=$principal,descripcion_corta='$descripcion' where c_fotos=" . $datos['cfoto'][$i], $this->db);
            }
        }
        if($datos['plano-descartado']=="1"){
            queryBD("delete from inmuebles_fotos where c_fotos=". $datos['plano-code'], $this->db);
        }
        else if(strlen($imagenes['plano']['name'])>0){
            $plano = convertirAJPG(array("name" => $imagenes['plano']['name'], "tmp_name" => $imagenes['plano']['tmp_name']));
            $nombre = "plano_".$referencia;
            $ruta = getUrl() . "/images/inmuebles/$nombre.jpg";
            if (imagejpeg($plano, getRoot() . "images/inmuebles/$nombre.jpg")) {
                if(count($this->getPlano($datos['cinmueble']))==0)
                    queryBD("insert into inmuebles_fotos(c_inmuebles,activo_01,url) values($datos[cinmueble],1,'$ruta')", $this->db);
                else
                    queryBD("update inmuebles_fotos set url=\"$ruta\" where c_fotos=" . $datos['plano-code'], $this->db);
            }
        }
        ejecutarHooks("updatePictures",$this->db,array("cinmueble"=>$datos['cinmueble'],"table"=>"inmuebles_fotos"));
    }

    function eliminarFoto($id) {
        $foto=queryBD("select * from inmuebles_fotos where c_fotos=$id",$this->db);
        unlink(str_replace(getUrl(),getRoot(),$foto[0]['url']));
        queryBD("delete from inmuebles_fotos where c_fotos=$id", $this->db);
    }
    static function getEtiquetaCertificacion($consumo,$emisiones){
        $escala=array("A","B","C","D","E","F","G");
        $tipos=array("consumo","emisiones");
        $baremos=array(
            "consumo-A"=>55,
            "consumo-B"=>75,
            "consumo-C"=>90,
            "consumo-D"=>100,
            "consumo-E"=>110,
            "consumo-F"=>125,
            "consumo-G"=>INF,
            "emisiones-A"=>5.6,
            "emisiones-B"=>9.7,
            "emisiones-C"=>15.9,
            "emisiones-D"=>24.9,
            "emisiones-E"=>53.2,
            "emisiones-F"=>60.1,
            "emisiones-G"=>INF,
            );
        $etiqueta=file_get_contents(getRoot()."/controllers/Inmuebles/View/certificacion.html.php");
        $consumoReemplazado=false;
        $emisionesReemplazado=false;
        foreach($tipos as $tipo){
            foreach($escala as $letra){
                $reemplazo="";
                if(!$consumoReemplazado && $tipo=="consumo" && $baremos["$tipo-$letra"]>$consumo){
                    $reemplazo="<span>".($consumo+0)."</span>";
                    $consumoReemplazado=true;
                }
                if(!$emisionesReemplazado && $tipo=="emisiones" && $baremos["$tipo-$letra"]>$emisiones){
                    $reemplazo="<span>".($emisiones+0)."</span>";
                    $emisionesReemplazado=true;
                }
                $etiqueta=str_replace("[$tipo-$letra]",$reemplazo,$etiqueta);
            }
        }
        return $etiqueta;
    }
    function translateValues($key,$value){
        $return=$value;
        switch($key){
            case 'precio_compra':
                $return=number_format((int)$value,0,',','.')." €";
            break;
            case 'zona':
                if($this->zonas==null){
                    $this->zonas=new Zonas($this->db);
                    $this->zonas->cargar();
                }
                $zona=$this->zonas->buscar('id', (int)$value);
                $return=$zona['nombre'];
            break;
            case 'tipo':
                if($this->tipos==null){
                    $this->tipos=new Tipos($this->db);
                    $this->tipos->cargar();
                }
                $tipo=$this->tipos->buscar('id', (int)$value);
                $return=$tipo['nombre'];
            break;
            case 'gestion':
                if($this->gestiones==null){
                    $this->gestiones=new Gestiones($this->db);
                    $this->gestiones->cargar();
                }
                $gestion=$this->gestiones->buscar('id', (int)$value);
                $return=$gestion['nombre'];
            break;
            case 'c_inmuebles':
                $pcpal=self::getFotoPrincipal($value, $this->db);
                if(count($pcpal)==0){
                    $pcpal['url']=getUrl()."/img/LogoAliaNegro.png";
                    $clase=" logo";
                }
                else $clase="";
                $return="<div class=\"wrap$clase\" style=\"background-image:url('".$pcpal['url']."')\"></div>";
            break;
            case 'estado_gestion':
                $disponibilidades=array(0=>"disponible",1=>"reservado",2=>"vendido");
                $return="<i class=\"fa fa-circle ".$disponibilidades[$value]."\"></i>";
            break;
            case 'compartida_01':
                $return=($value==1)?"Si":"No";
            break;
            case 'fecha_registro':
            case 'fecha_venta':
                $auxiliar=explode("-",$value);
                $return=$auxiliar[2]."/".$auxiliar[1]."/".$auxiliar[0];
            break;
            case 'id_empresa':
            case 'compradora':
                if($this->empresas==null){
                    $this->empresas=new Empresas($this->db);
                    $this->empresas->cargar();
                }
                $empresa=$this->empresas->buscar('id', (int)$value);
                $return=$empresa['codigo_empresa'];
            break;
            case 'estado_vivienda':
                if($value!=-1){
                    $estados=array(1=>"Nuevo",2=>"Buen Estado",3=>"Obra Nueva",4=>"A Reformar",5=>"Reformado");
                    $return=$estados[$value];
                }
            break;
            case 'antiguedad':
                if($value!=-1){
                    $antiguedades=array(1=>"1 a 5",2=>"5 a 20",3=>"20 a 40",4=>"+40");
                    $return=$antiguedades[$value]." años";
                }
            break;
            case 'orientacion_solar':
                if($value!=-1){
                    $orientacion=array(0=>"Norte",1=>"Sur",2=>"Este",3=>"Oeste",4=>"Noreste",5=>"Noroeste",6=>"Sureste",7=>"Suroeste");
                    $return=$orientacion[$values];
                }
            break;
            case 'suelos':
                if($value!=-1){
                    $suelos=array(0=>"Cerámico/Gres",1=>"Parque",2=>"Tarima",3=>"Otros");
                    $return=$suelos[$value];
                }
            break;
            case 'calefaccion':
                if($value!=-1){
                    $calefacciones=array(0=>"Sin Calefacción",1=>"Central",2=>"Eléctrica",3=>"Central",4=>"Gas",5=>"Gasoil",6=>"Otros");
                    $return=$calefacciones[$value];
                }

            break;
            case 'accesible_01':
            case 'amueblado_01':
            case 'garaje_01':
            case 'aire_acondicionado_01':
            case 'piscina_01':
            case 'jardin_01':
            case 'trastero_01':
            case 'ascensor_01':
            case 'terraza_01':
            case 'vpo_01':
            case 'armarios_empotrados_01':
            case 'tendedero_01':
            case 'portero_01':
            case 'urbanizacion_01':
                if($value!=-1){
                    $return=($value==1)?"Sí":"No";
                }
            break;
            case 'n_banos':
            case 'n_aseos':
                if($value==0) $return=-1;
                elseif($value==4) $return="$value+";
            break;
        }
        return $return;
    }
    function busquedaDeInmuebles($array){
        $condiciones=array();
        foreach($array as $key=>$value){
            switch($key){
                case 'precio_compra':
                    $condiciones[]="$key>=0";
                break;
                case 'precio_alquiler':
                    $condiciones[]="$key>=0";
                break;
                case 'n_habitaciones':
                    $condiciones[]="$key<=$value";
                break;
                case 'referencia':
                    $condiciones[]="$key like '%$value%'";
                break;
                default:
                    $condiciones[]="$key=$value";
                break;
            }
        }
        $query="select * from $this->table";
        $query.=(count($condiciones)>0)?" where ". implode(" AND ",$condiciones):"";
        $resultados=queryBD($query ,$this->db);
        return $resultados;
    }
    function buscarReferencia($referencia){
        $aux=queryBD("select * from $this->table where referencia like \"%$referencia%\"",$this->db);
        return $aux[0];
    }
    function buscarZona($zona){
        $aux=queryBD("select * from $this->table where zona=$zona",$this->db);
        return $aux;
    }
    function replaceInList($line,$id){
        if(count($this->registros)==0) $this->cargar();
        $referencia=$this->buscar("c_inmuebles",$id);
        return str_replace("[referencia]",$referencia['referencia'],$line);
    }
    function getDocumentos($id){
        $url=getUrl()."/";
        $documentos=queryBD("select concat('$url',url) as url,identificador from inmuebles_documentos where c_inmuebles=$id",$this->db);
        return $documentos;
    }
    function enviarEmail($datos){
        include_once(getRoot()."controllers/Empresas/Controller/empresas.class.php");
        include_once(getRoot()."controllers/Mail/Controller/mail.class.php");
        $config=getConfiguration("Compania");
        $mail=new Mail();
        $empresasController=new Empresas($this->db);
        $empresasController->cargar();
        $empresa=$empresasController->buscar("id",$datos['id_empresa']);
        $empresas=$empresasController->obtenerRegistros();
        $mail->setDestiny($empresa["email"],$empresa["nombre"]);
        for($i=0;$i<count($empresas);$i){
            if($empresas[$i]['id']!=$datos['id_empresa'])
                $mail->addAddress($empresas[$i]["mail"],$empresas[$i]['nombre']);
        }
        $mail->setRemiter($config["correo"],$config['nombre']);
        $mail->setMessage(array('empresa'=>$empresa['nombre'],'referencia'=>$datos['referencia']),"Inmueble insertado por ".$empresa['nombre'],"inmueble-insertado");
        $mail->send();
    }
}

?>
