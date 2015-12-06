<head>
    <meta charset="utf-8">
</head>
<table>
<?php
$i=0;
    include_once("../processing/library.php");
    function soloNumeros($text){
        preg_match_all("(\d+)", $text, $matches);
        return $matches[0][0];
    }
    //$file = fopen("../docs/CLIENTES-COMPRADORES.csv", "r") or exit("Unable to open file!");
    $file = fopen("../docs/CLIENTES-INQUILINOS-ALQUILER.csv", "r") or exit("Unable to open file!");
    while(!feof($file)){
        $i++;
        list($codigo,$nombre,$apellidos,$fechaExp,$tlf,$tlf2,$mail,$mail2,$agente,$zona,$hab,$refInt,$presup,$como,$notas,$fechaEnt)=explode(";",str_replace("\"","",fgets($file)));
        $tlf=soloNumeros($tlf);
        $tlf2=soloNumeros($tlf2);
        $queryCli="insert into clientes(primera_importacion,codigo_primera_importacion,nombre,apellidos,telefono,telefono_1,mail,mail_1,comprador,vendedor,inquilino,arrendatario,notas)"
                . " values(true,\"$codigo\",\"$nombre\",\"$apellidos\",\"$tlf\",\"$tlf2\",\"$mail\",\"$mail2\",1,0,0,0,\"$notas\")";
        $queryDem="insert into demandas(fecha_creacion,dormitorios,id_cliente,presupuesto,id_agente,fecha_expiracion,c_zona,referencia_interes)"
                . " values(\"$fechaEnt\",$hab,$id,$presup,$agente,NULL,$zona,\"$refInt\");";
        /*echo $queryCli ."<br>";
        echo $queryDem ."<br>";
        ?>
    <tr>
        <td><?php echo $codigo; ?></td>
        <td><?php echo $nombre; ?></td>
        <td><?php echo $apellidos; ?></td>
        <td><?php echo $fechaExp; ?></td>
        <td><?php echo $tlf; ?></td>
        <td><?php echo $tlf2; ?></td>
        <td><?php echo $mail; ?></td>
        <td><?php echo $mail2; ?></td>
        <td><?php echo $agente; ?></td>
        <td><?php echo $zona; ?></td>
        <td><?php echo $hab; ?></td>
        <td><?php echo $refInt; ?></td>
        <td><?php echo $presup; ?></td>
        <td><?php echo $como; ?></td>
        <td><?php echo $notas; ?></td>
        <td><?php echo $fechaEnt; ?></td>
    </tr>
<?php
    */}
    echo $i;
    fclose($file);
?>
</table>