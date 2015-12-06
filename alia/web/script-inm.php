<head>
    <meta charset="utf-8">
</head>
<table>
<?php
ini_set("display_errors",1);
$i=0;
    include_once("../processing/library.php");
    function soloNumeros($text){
        preg_match_all("(\d+)", $text, $matches);
        return $matches[0][0];
    }
    $file = fopen("../docs/inm.csv", "r") or exit("Unable to open file!");
    while(!feof($file)){
        echo "<tr style=\"border-bottom:1px solid black;\">";
        $celdas=explode(";",str_replace("\"","",fgets($file)));
        foreach($celdas as $celda)
            echo "<td style=\"border-bottom:1px solid black;\">".utf8_decode (strip_tags ($celda))."</td>";
        echo "</tr>";
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
    fclose($file);
?>
</table>