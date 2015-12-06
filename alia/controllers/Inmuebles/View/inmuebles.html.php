<?php
    include_once(getRoot() . "/controllers/Base/View/base_admin.html.php");
    include_once(getRoot() . "/controllers/Zonas/Controller/zonas.class.php");
    include_once(getRoot() . "/controllers/Empresas/Controller/empresas.class.php");
    $zonas = new Zonas($this->getDB());
    $zonas->cargar();
    $empresasController= new Empresas($this->getDB());
    $empresasController->cargar();
    $this->cargarPanel($_SESSION['usuario']['permisos'],$_SESSION['usuario']['codigo_empresa']);
    $referenciaSiguiente=Inmuebles::getSiguienteReferencia($this->getDB());
    $mostrar = $this->getLista("Mostrar_Admin");
    $fotos = $this->getFormulario("Fotos");
    $vendido= $this->getFormulario("Vendido");
?>
<body>
    <?php include_once(dirname(__FILE__)."/../Includes/init-inmuebles.html.php"); ?>
    <div id="wrapper">
        <?php include_once("header.php"); ?>
        <div class="plegado">
            <?php Formularios::printFormulario($fotos, "templateFotos"); ?>
        </div>
        <div class="plegado">
            <?php Formularios::printFormulario($vendido); ?>
        </div>
        <div class="plegado">
            <?php Formularios::printFormulario($this->getFormulario("Nuevo"),null,"template03"); ?>
        </div>
        <div class="plegado">
            <?php Formularios::printFormulario($this->getFormulario("Modificar"),null,"template03"); ?>
        </div>
        <?php include_once("bloque-exportacion.php"); ?>
        <?php include_once("bloque-importacion.php"); ?>
        <?php include_once("bloque-busqueda-nuevo.php") ?>
        <?php Listados::printListado($mostrar,$this); ?>
    </div>
</body>
