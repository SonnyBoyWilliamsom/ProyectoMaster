<?php
    /*Includes*/
    include_once("../processing/library.php");
    $root=getRoot();
    include_once($root."processing/session.php");

    $controller=$_GET['controller'];
    $format=$_GET['format'];

    include_once($root."controllers/$controller.class.php");
    $db=connectBD();
    $data=new $controller($db,false);
    $data->cargar();
    $registros=$data->obtenerRegistros();
    switch($format){
        case "xml":
            header('Content-Type: text/xml; charset=utf-8');
            header('Content-Disposition: attachment; filename="'.$controller.'.xml"');
            $xml="<$controller>";
            foreach($registros as $reg){
                $xml.="<". substr($controller,0,-1) .">";
                foreach($reg as $key=>$value){
                    $xml.= "<$key><![CDATA[". utf8_encode($value)."]]></$key>";
                }
                $xml.= "</". substr($controller,0,-1) .">";
            }
            $xml.="</$controller>";
            echo $xml;
        break;
        case "csv":
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="'.$controller.'.csv"');
            $csv="";
            foreach($registros[0] as $key=>$value)
                $csv.="\"$key\";";
            foreach($registros as $reg){
                $csv.="\r\n";
                foreach($reg as $value){
                    $csv.="\"". utf8_encode($value) ."\";";
                }
            }

            echo $csv;
        break;
    }
    disconnectBD($db);
?>