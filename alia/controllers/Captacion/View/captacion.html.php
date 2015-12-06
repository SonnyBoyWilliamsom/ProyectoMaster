<?php
include_once(getRoot() . "/controllers/Base/View/base_admin.html.php");
$this->cargar();
$mostrar=$this->getLista("Mostrar_Admin");
$formNuevo=$this->getFormulario("Nuevo");
$formModificar=$this->getFormulario("Modificar");
?>
<body>
    <script>
    $(document).ready(function(){
        $("form").validate(true);
    });
    </script>
    <div id="wrapper">
        <?php include_once("header.php"); ?>
        <div class="plegado">
            <?php Formularios::printFormulario($formNuevo); ?>
        </div>
        <div class="plegado">
            <?php Formularios::printFormulario($formModificar); ?>
        </div>
        <?php include_once("bloque-exportacion.php"); ?>
        <?php include_once("bloque-importacion.php"); ?>
        <?php include_once("bloque-busqueda-nuevo.php") ?>
        <?php Listados::printListado($mostrar,$this); ?>
    </div>
</body>