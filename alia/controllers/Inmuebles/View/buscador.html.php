<?php
    include_once(getRoot()."/controllers/Tipos/Controller/tipos.class.php");
    include_once(getRoot()."/controllers/Gestiones/Controller/gestiones.class.php");
    include_once(getRoot()."/controllers/Zonas/Controller/zonas.class.php");
    $referencia=$_POST['referencia'];
    $tipo=$_POST['tipo']+0;
    $habitaciones=$_POST['n_habitaciones']+0;
    $zona=$_POST['zona']+0;
    $arrayBusqueda=array("activo_01"=>1);
    if($habitaciones>0)  $arrayBusqueda['n_habitaciones']=$habitaciones;
    if($tipo>0)  $arrayBusqueda['tipo']=$tipo;
    if($zona>0)  $arrayBusqueda['zona']=$zona;
    $inmuebles=$this->busquedaDeInmuebles($arrayBusqueda);
    $inmueblesController=$this;
    $campos=$this->getLista("Buscador");
    $aComparar=array();
    for($i=0;$i<count($_SESSION['comparar']);$i++)
        $aComparar[]="#".$_SESSION['comparar'][$i]." a:first";
    $aCompararJquery=implode(",",$aComparar);

    $buscador=$this->getFormulario("Buscador");

?>
<body>
    <script>
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
    <div class="buscadorFlotante" style="display:none;">
        <?php Formularios::printFormulario($buscador,$this->getDB()); ?>
    </div>
    <main>
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
