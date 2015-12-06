<?php
    session_start();
    include_once(getRoot()."/controllers/Tipos/Controller/tipos.class.php");
    include_once(getRoot()."/controllers/Gestiones/Controller/gestiones.class.php");
    include_once(getRoot()."/controllers/Zonas/Controller/zonas.class.php");

    $this->cargar();
    $inmuebles=array();
    $inmuebles[]=$this->buscar("c_inmuebles",$_SESSION['comparar'][0]);
    $inmuebles[]=$this->buscar("c_inmuebles",$_SESSION['comparar'][1]);
    $campos=$this->getLista("Comparador");
    $inmueblesController=$this;
    //unset($_SESSION['comparar']);
?>
<body>
    <div id="wrapper">
        <?php include_once("header.php"); ?>
        <div id="comparador">
        <?php for($i=0;$i<count($inmuebles);$i++):
            $imgPcpal=self::getFotoPrincipal($inmuebles[$i]['c_inmuebles'],$this->db);

        ?>
            <div class="inmueble">
                <p class="referencia">Inmueble <a target="_blank" href="<?php echo getUrl()."/inmuebles/ficha/".$inmuebles[$i]['referencia']; ?>" title="<?php echo $inmuebles[$i]['referencia']; ?>"><?php echo $inmuebles[$i]['referencia']; ?></a></p>
                <div>
                    <img src="<?php echo $imgPcpal['url']; ?>" />
                </div>
                <div class="datos">
                    <?php for($j=0;$j<count($campos['cells']);$j++): ?>
                    <div class="dato">
                        <p class="nombre"><?php echo $campos['cells'][$j]['data']['label_es']?></p>
                        <p class="valor"><?php echo $inmuebles[$i][$campos['cells'][$j][0]['key_bd']]?></p>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endfor; ?>
        </div>
        <?php include_once("footer.php"); ?>
    </div>
</body>
