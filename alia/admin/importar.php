<?php
	ini_set("display_errors",1);
	/*Includes*/
	include_once("../processing/library.php");
	$root=getRoot();
	include_once($root."processing/session.php");
	
	$controller=$_POST['controller'];
	$archivo=$_FILES['archivo'];
	
	include_once($root."/controllers/$controller.class.php");
?>
    <!DOCTYPE>
	<html lang="es">
    	<head>
        	<meta charset="utf-8" />
	        <title>Nuevo Milenio - Administracion</title>
    	    <link rel="stylesheet" type="text/css" href="../style/admin.css" />
        	<script type="text/javascript" src="../js/jquery.js"></script>
	        <script type="text/javascript" src="../js/javascript.js"></script>
                <script>
                    $(document).ready(function(){
                        $("td").click(editar);
                    });
                </script>
    	</head>
    <body>
    <?php
        $format=substr($archivo['name'],-3,3);
        $nodo=substr($controller,0,-1);
        $db=connectBD();
        $header=array();
        $values=array();
        switch($format){
            case "xml":
                $xml=simplexml_load_file($archivo['tmp_name']);
                for($i=0; $i<count($xml); $i++){
                    $values[$i]=array();
                    foreach($xml->{"$nodo"}[$i]->children() as $key=>$registro){
                        $values[$i][$key]=(string)$registro;
                        if($i==0) $header[]=$key;
                    }
                }
            break;
            case "csv":
                $csv=fopen($archivo['tmp_name'],"rt");
                $i=0;
                while($line=fgets($csv)){
                    if($i++==0){
                        $aux=explode(";",str_replace(array("\"","\r","\n","\t"),array("","","",""),$line));
                        unset($aux[count($aux)-1]);
                        $header=$aux;
                    }
                    else{
                        $aux=explode(";",str_replace("\"","",$line));
                        for($j=count($aux);$j<count($header);$j++){
                            $aux[$j]="";
                        }
                        if(count($aux)==count($header)+1) unset($aux[count($aux)-1]);
                        $values[]=array_combine($header,$aux);
                    }
                }
                fclose($csv);
            break;
        }
        echo "<table id=\"importacion\">";
        $campos=$controller::getFormulario($db);
        unset($campos["datos"]);
        echo "<tr><th>Seleccionar</th>";
        for($i=0;$i<count($header);$i++){
            echo "<th><select onchange=\"cambiarCampoExportacion('#importacion select',$(this))\"><option value=\"-1\"></option>";
            $j=0;
            foreach($campos as $campo){
                echo "<option value=\"". $campo['key_bd'] ."\"";
                if($campo['key_bd']==$header[$i] || $campo['label_es']==$header[$i]) echo " selected ";
                echo ">". $campo['label_es'] ."</option>";
            }
            echo "</select></th>";
        }
        echo "</tr>";
        foreach($values as $row){
            echo "<tr>";
            echo "<td><input type=\"checkbox\" name=\"chk[]\" checked></td>";
            foreach($row as $field=>$cell)
                echo "<td class=\"$field\">".utf8_encode ($cell)."</td>";
            echo "</tr>";
        }
        echo "</table>";
        $object=new $controller($db);
        echo "<a href=\"javascript:void(0);\" onclick=\"javascript:importar('#importacion tbody','". $object->getName() ."',". $object->getAjaxIndex("import") .");\">Realizar importacion</a>";
		echo "<a href=\"javascript:void(0);\" onclick=\"javascript:window.close()\">Terminar importacion</a>";
        disconnectBD($db);
    ?>
    <p class="avisos"></p>
    </body>
</html>