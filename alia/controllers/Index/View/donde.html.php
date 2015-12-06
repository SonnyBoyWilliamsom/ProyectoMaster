<?php
/* Inicialización */
include_once(getRoot() . "/controllers/Inmuebles/Controller/inmuebles.class.php");
include_once(getRoot() . "/controllers/Tipos/Controller/tipos.class.php");
include_once(getRoot() . "/controllers/Zonas/Controller/zonas.class.php");
include_once(getRoot() . "/controllers/Formularios/Controller/formularios.class.php");


$gestiones = array("compra", "alquiler", "traspaso", "alquiler_opcion_compra");

$tipos = new Tipos($this->getDB());
$zonas = new Zonas($this->getDB());
$poblaciones = new Poblaciones($this->getDB());
$inmueblesController = new Inmuebles($this->db);
$inmueblesController->cargar();
$tipos->cargar();
$poblaciones->cargar();
$zonas->cargar();
$inmuebles = $inmueblesController->obtenerRegistros();
$llamada = $this->getFormulario("Llamada");
$contacto=$this->getFormulario("Contacto");
?>
<body>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&;amp;language=es"></script>
    <script>
        $(document).ready(function(){
            var alto=$(window).height()-$("header").height()-$("footer").height();
            $("#mapa").height(alto);
            var coordenadas=new google.maps.LatLng(40.3472439,-3.8408952);
            var icono=direccion+"/img/icon-maps.png";
            var options = {
                zoom: 14,
                center: coordenadas,
                mapTypeId: google.maps.MapTypeId.MAP
            };
            var marker = new google.maps.Marker({
                position: coordenadas,
                map: map,
                title:"Hola Mundo!",
                icon: icono
            });
            var map = new google.maps.Map(document.getElementById('mapa'), options);
            marker.setMap(map);
            var transitLayer = new google.maps.TransitLayer();
            transitLayer.setMap(map);
        });
    </script>
    <div id="wrapper">
        <?php include_once("header.php"); ?>
        <div id="mapa"></div>
        <?php include_once("footer.php"); ?>
    </div>
</body>
