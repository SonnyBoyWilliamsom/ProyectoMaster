<?php
if(isset($_POST['query'])){
    $selector=0+$_POST['query'];
    $demandas=new Demandas($db);
    switch($selector){
        case 0: //Recuperar
            $id=$_POST['id_cliente'];
            $demandas->cargar($id);
            $demandasDeCliente=$demandas->obtenerRegistros();
            if(count($demandasDeCliente)==0) echo 0;
            else print_r($demandasDeCliente);
        break;
        case 1: //Insertar
            $datos=$_POST;
            $datos['fecha_creacion']=date("Y-m-d");
            unset($datos['id']);
            unset($datos['submit']);
            unset($datos['query']);
            unset($datos['controller']);
            $demandas->insertar($datos);
        break;
        case 3: //Eliminar
            $id=$_POST['id'];
            $demandas->eliminar($id);
            echo "0";
        break;
        case 4: //Eliminar varios
            $ids=$_POST['id'];
            $demandas->eliminarVarios($ids);
            echo "0";
        break;
        case 5://Modificar
            $datos=$_POST;
            unset($datos['query']);
            unset($datos['controller']);
            unset($datos['submit']);
            $demandas->modificar($datos);
        break;
        case 6://Get Agente
            $id=$_POST['id'];
            $demandas->cargar();
            $demanda=$demandas->buscar("id", $id);
            print_r(json_encode($demanda));
        break;
        case 7://Get Demand
            $id=$_POST['id'];
            $demandas->cargar();
            $demanda=$demandas->buscar("id_cliente", $id);
            print_r(json_encode($demanda));
        break;
        case 8://Get presupuestos
            $valor=$_POST['valor']+0;
            $presupuestos=array(1=>array('comienzo'=>100000,'incremento'=>50000,'tope'=>600000),2=>array('comienzo'=>100,'incremento'=>100,'tope'=>1500));
            $options[]="-";
            if(isset($presupuestos[$valor])){
                for($i=$presupuestos[$valor]['comienzo'];$i<=$presupuestos[$valor]['tope'];$i+=$presupuestos[$valor]['incremento'])
                    $options[]=$i;
                print_r(json_encode($options));
            }
        break;
        case 9://Búsqueda Avanzada

            $datos=$_POST;
            unset($datos['query']);
            unset($datos['controller']);
            unset($datos['submit']);
            unset($datos['ajax']);
            print_r(json_encode($demandas->buscadorAvanzado($datos)));
            die();
        break;
    }
    if(!isset($_POST['ajax']) || !$_POST['ajax']) header("Location: ".$_SERVER['HTTP_REFERER']);
}
