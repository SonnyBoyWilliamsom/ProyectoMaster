<?php
    include_once(getRoot()."/controllers/Tipos/Controller/tipos.class.php");
    include_once(getRoot()."/controllers/Gestiones/Controller/gestiones.class.php");
    include_once(getRoot()."/controllers/Zonas/Controller/zonas.class.php");
    if(isset($_GET['param'])){
        $parametro=$_GET['param'];
        $valor=(isset($_GET['value']))?$_GET['value']:$_POST['referencia'];
        $inmuebles=$this->busquedaDeInmuebles(array($parametro=>$valor,"activo_01"=>"1"));
    }
    else{
        $this->cargar();
        $inmuebles=$this->obtenerRegistros();
    }
    $campos=$this->getLista("Buscador");
    $inmueblesController=$this;
    $buscador=$this->getFormulario("Buscador");
?>
<body>
    <script>
        var zona=<?php echo $zona; ?>
        $(document).ready(function(e){
            $("#resultados table tr").click(function(e){
                var link=$(e.currentTarget).children("td").children("a");
                if(link.length>0){
                    location.href=link.prop("href");
                }
                e.preventDefault;
                e.stopPropagation;
            })
        });
    </script>
    <?php include_once("header.php"); ?>
    <main>

        <div class="buscadorFlotante" style="display:none;">
            <?php Formularios::printFormulario($buscador,$this->getDB()); ?>
        </div>
        <div id="resultados">
            <p class="encontrados">Se han encontrado <?php echo count($inmuebles); ?> inmuebles</p>
            <ul class="leyenda">
                <li><i class="fa fa-circle disponible"></i> Disponible</li>
                <li><i class="fa fa-circle reservado"></i> Reservado</li>
                <li><i class="fa fa-circle vendido"></i> Vendido</li>
            </ul>
            <?php Listados::printListado($campos, $this,"buscador-inmueble",$inmuebles); ?>
        </div>
    </main>
    <?php include_once("footer.php"); ?>
</body>
