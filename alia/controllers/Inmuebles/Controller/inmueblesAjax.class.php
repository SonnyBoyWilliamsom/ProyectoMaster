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
            $inmuebleAux=queryBD("select * from inmuebles where c_inmuebles=$id",$db);
            $inmueble=$inmuebleAux[0];

            $inmueble['fecha_captacion']=formatDate($inmueble['fecha_captacion']);
            $inmueble['fecha_fin_mandato']=formatDate($inmueble['fecha_fin_mandato']);

            print_r(json_encode($inmueble));
        break;
        case 7:
            $datos=$_POST;
            unset($datos["codificada"]);
            $imagenes=$_POST["codificada"];
            $cInmueble=$datos['cinmueble'];
            unset($datos['cinmueble']);
            unset($datos['query']);
            unset($datos['controller']);
            unset($datos['submit']);
            $inmuebles->guardarFotos($datos,$imagenes,$cInmueble);
        break;
        case 8:
            $datos=$_POST;
            $imagenes=array("fotos"=>$inmuebles->getFotos($datos['id']));
            print_r(json_encode($imagenes));
        break;
        case 9:
            $datos=$_POST;
            unset($datos["codificada"]);
            $imagenes=$_POST["codificada"];
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
        case 11:
            $id=$_POST['id'];
            session_start();
            if(!isset($_SESSION['comparar'])) $_SESSION['comparar']=array();
            if(!in_array($id,$_SESSION['comparar'])) $_SESSION['comparar'][]=$id;
            if(count($_SESSION['comparar'])>=2) print_r(json_encode(array("redirect"=>true)));
            else print_r(json_encode($_SESSION['comparar']));
        break;
        case 12:
            $order=$_POST['order'];
            $ids="(".implode(",",$_POST['ids']).")";
            $query="select c_inmuebles from inmuebles where c_inmuebles in $ids order by precio_compra $order";
            $resultado=queryBD($query,$inmuebles->getDB());
            print_r(json_encode($resultado));
        break;
        case 13:
            $order=$_POST['order'];
            $ids="(".implode(",",$_POST['ids']).")";
            $query="select c_inmuebles from inmuebles as i inner join zonas as z on i.zona=z.id where c_inmuebles in $ids order by z.nombre $order";
            $resultado=queryBD($query,$inmuebles->getDB());
            print_r(json_encode($resultado));
        break;
        case 14:
            $id=$_POST['c_inmuebles'];
            $fechaVenta=$_POST['fecha_venta'];
            $precio=$_POST['precio_vendido'];
            $compradora=$_POST['compradora'];
            $compartida=(int)isset($_POST['compartida']);
            $aux = explode("/", $fechaVenta);
            $fechaVenta=$aux[2] . "-" . $aux[1] . "-" . $aux[0];
            $query="update inmuebles set fecha_venta='$fechaVenta',precio_venta=$precio,compradora=$compradora,compartida_01=$compartida,estado_gestion=2 where c_inmuebles=$id";
            queryBD($query,$inmuebles->getDB());
        break;
        case 15:
            $order=$_POST['order'];
            $ids="(".implode(",",$_POST['ids']).")";
            $query="select c_inmuebles from inmuebles where c_inmuebles in $ids order by estado_gestion $order";
            $resultado=queryBD($query,$inmuebles->getDB());
            print_r(json_encode($resultado));
        break;
    }
    if(!isset($_POST['ajax']) || !$_POST['ajax']) header("Location: ".$_SERVER['HTTP_REFERER']);
}
?>
