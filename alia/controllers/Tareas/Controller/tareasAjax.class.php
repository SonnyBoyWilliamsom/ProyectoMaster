<?php
if(isset($_POST['query'])){
    $selector=0+$_POST['query'];
    $inmuebles=new Inmuebles($db);
    switch($selector){
        case 0: //Insertar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['submit']);
            unset($datos['c_inmuebles']);
            unset($datos['controller']);
            $inmuebles->insertar($datos);
        break;	
        case 1: //Eliminar
            $id=$_POST['id'];
            $inmuebles->eliminar($id);
            echo "0";
        break;
        case 2: //Eliminar Varios
            $ids=$_POST['id'];
            $inmuebles->eliminarVarios($ids);
            echo "0";
        break;
        case 3: //Importar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['ajax']);
            echo $inmuebles->insertarStatic($datos);
        break;
        case 4://Nuevo Campo
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['ajax']);
            unset($datos['submit']);
            $datos['id']=Agentes::getID();
            include_once("formularios.php");
            $inmuebles->nuevoCampo($datos);
        break;
        case 5://Modificar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['controller']);
            unset($datos['submit']);
            if(!isset($datos['exclusividad_01'])) $datos['exclusividad_01']=0;
            else  $datos['exclusividad_01']=1;
            
            if(!isset($datos['reservado_01'])) $datos['reservado_01']=0;
            else  $datos['reservado_01']=1;
            
            if(!isset($datos['vpo_01'])) $datos['vpo_01']=0;
            else  $datos['vpo_01']=1;
            
            if(!isset($datos['piso_banco_01'])) $datos['piso_banco_01']=0;
            else  $datos['piso_banco_01']=1;
            
            if(!isset($datos['eficiencia_energetica_entramite_01'])) $datos['eficiencia_energetica_entramite_01']=0;
            else  $datos['eficiencia_energetica_entramite_01']=1;
            
            if(!isset($datos['activo_01'])) $datos['activo_01']=0;
            else  $datos['activo_01']=1;
            
            if(!isset($datos['direccion_completa_01'])) $datos['direccion_completa_01']=0;
            else  $datos['direccion_completa_01']=1;
            
            if(!isset($datos['direccion_visible_01'])) $datos['direccion_visible_01']=0;
            else  $datos['direccion_visible_01']=1;
            
            if(!isset($datos['posicion_exacta_01'])) $datos['posicion_exacta_01']=0;
            else  $datos['posicion_exacta_01']=1;
            
            $inmuebles->modificar($datos);
        break;
        case 6://Get Inmueble
            include_once(getRoot()."/controllers/Clientes/Controller/clientes.class.php");
            $id=$_POST['id'];
            $inmuebles->cargar();
            $inmueble=$inmuebles->buscar("c_inmuebles", $id);
            $clientes=new Clientes($inmuebles->getDB());
            $clientes->cargar();
            $cliente=$clientes->buscar("id", $inmueble['id_cliente']);
            $inmueble['nombre_cliente']=$cliente['nombre']." ".$cliente['apellidos'];
            print_r(json_encode($inmueble));
        break;
        case 7:
            $datos=$_POST;
            $imagenes=$_FILES;
            $cInmueble=$datos['cinmueble'];
            unset($datos['cinmueble']);
            unset($datos['query']);
            unset($datos['controller']);
            unset($datos['submit']);
            $inmuebles->guardarFotos($datos,$imagenes,$cInmueble);
        break;
        case 8:
            $datos=$_POST;
            $imagenes=array("fotos"=>$inmuebles->getFotos($datos['id']),"plano"=>$inmuebles->getPlano($datos['id']));
            print_r(json_encode($imagenes));
        break;
        case 9:
            $datos=$_POST;
            $imagenes=$_FILES;
            $inmuebles->modificarFotos($datos, $imagenes);
        break;
        case 10:
            $datos=$_POST;
            if(isset($datos['referencia']) && strlen($datos['referencia'])>0){
                $inmuebles->cargar();
                $inmueble=$inmuebles->buscar("referencia", $datos['referencia']);
                header("Location: ".  getUrl()."/inmuebles/ficha/".$inmueble["c_inmuebles"]);
            }
            else{
                unset($datos['referencia']);
                unset($datos['submit']);
                unset($datos['controller']);
                unset($datos['query']);
                switch($datos["gestion"]){
                    case "Alquilar":
                        $datos["precio_alquiler"]=0;
                    break;
                    case "Comprar":
                        $datos["precio_compra"]=0;
                    break;
                }
                unset($datos["gestion"]);
                $query=array();
                foreach($datos as $key=>$dato){
                    if(is_numeric($dato) && $dato>0) $query[]=$key."=".$dato;
                    else if(strlen($dato)>0) $query[]=$key."<>".$dato;
                }
                $resultados=queryBD("select * from ".$inmuebles->getTable()." where ".implode(" and ",$query),$inmuebles->getDB());
                //header("Location: ".  getUrl()."/inmuebles/resultados/");
            }
            die();
        break;
    }
    if(!isset($_POST['ajax']) || !$_POST['ajax']) header("Location: ".$_SERVER['HTTP_REFERER']);
}
?>