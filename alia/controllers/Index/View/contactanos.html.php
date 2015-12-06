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
    <div id="wrapper">
        <?php include_once("header.php"); ?>
        <section id="contacto">
            <div class="limitado">
                <p class="titulo">Dirección, teléfono, e-mail, formulario… Tu elijes</p>
                <p>La inmobiliaria especializada en pisos en Parque Oeste, pisos en Las Retamas y pisos en Alcorcón en general se encuentra en:</p>
                <div id="datos">
                    <ul>
                        <li><i class="fa fa-map-marker"></i><a href="<?php echo getUrl();?>/donde-estamos/">C/ Oslo 5, 28922 Alcorcón, MADRID</a></li>
                        <li><i class="fa fa-phone"></i><a href="tel:916890303">91 689 03 03</a></li>
                        <li><i class="fa fa-envelope"></i><a href="mailto:info@nuevomilenio-inmo.com">info@nuevomilenio-inmo.com</a></li>
                        <li><i class="fa fa-globe"></i>www.nuevomilenio-inmo.com</li>
                    </ul>
                </div>
                <div id="formulario">
                    <?php Formularios::printFormulario($contacto,$this->getDB()); ?>
                </div>
                <p class="datos-personales">Los datos personales facilitados serán tratados de forma confidencial, según legislación vigente (LOPD 15/1999), siendo almacenados en un fichero del que somos directos responsables. Su recogida tiene como finalidad la prestación de los servicios ofrecidos por la empresa y no serán cedidos a terceros, salvo consentimiento expreso. El derecho de modificación o cancelación puede ser ejercido enviando un email a: clientes@nuevomilenio-inmo.com.</p>
            </div>
        </section>
        <?php include_once("footer.php"); ?>
    </div>
</body>
