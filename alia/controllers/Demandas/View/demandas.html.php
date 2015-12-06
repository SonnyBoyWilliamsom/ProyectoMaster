<?php
include_once(getRoot() . "/controllers/Base/View/base_admin.html.php");
include_once(getRoot() . "/controllers/Zonas/Controller/zonas.class.php");
include_once(getRoot() . "/controllers/Clientes/Controller/clientes.class.php");
include_once(getRoot() . "/controllers/Tipos/Controller/tipos.class.php");
$this->cargar();
$provincias = new Provincias($this->getDB());
$poblaciones = new Poblaciones($this->getDB());
$zonas = new Zonas($this->getDB());
$clientes = new Clientes($this->getDB());
$tipos = new Tipos($this->getDB());

$poblaciones->cargar();
$provincias->cargar();
$zonas->cargar();
$clientes->cargar();
$tipos->cargar();
$mostrar=$this->getLista("Mostrar");
$campos = $this->getFormulario("Nuevo");
$modificar = $this->getFormulario("Modificar");

$eleccion = array(0 => "No", 1 => "Si");
?>
<body>
    <script>
        $(document).ready(function() {
            $("select.c_provincia").change(function() {
                $(".c_zona").children().remove();
                cargarPoblaciones($("select.c_provincia").val(), "select.c_poblacion",<?php echo $provincias->getAjaxIndex("poblaciones"); ?>);
            });
            $("select.c_poblacion").change(function() {
                cargarZonas($("select.c_poblacion").val(), "select.c_zona",<?php echo $poblaciones->getAjaxIndex("zonas"); ?>);
            });
            $("select.tipo").change(function(e){
                var elemento=$(e.currentTarget);
                var destino=elemento.parent().children("select.presupuesto");
                cargarPresupuestos($(e.currentTarget).val(),destino,'<?php echo $this->getName(); ?>',<?php echo $this->getAjaxIndex('presupuesto'); ?>);
            });
        });
    </script>
    <div id="wrapper">
        <?php include_once("header.php"); ?>
        <div class="plegado">
            <?php Formularios::printFormulario($campos); ?>
        </div>
        <div class="plegado">
            <?php Formularios::printFormulario($modificar); ?>
        </div>
        <?php include_once("bloque-exportacion.php"); ?>
        <?php include_once("bloque-importacion.php"); ?>
        <?php include_once("bloque-busqueda-avanzada.php") ?>
        <?php Listados::printListado($mostrar,$this); ?>
    </div>
</body>