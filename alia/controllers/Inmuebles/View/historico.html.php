<?php
ini_set("display_errors",1);
include_once(getRoot() . "/controllers/Base/View/base_admin.html.php");
include_once(getRoot() . "/controllers/Zonas/Controller/zonas.class.php");
include_once(getRoot() . "/controllers/Tipos/Controller/tipos.class.php");
include_once(getRoot() . "/controllers/Empresas/Controller/empresas.class.php");
$zonas = new Zonas($this->getDB());
$zonas->cargar();
$this->cargarHistorico($_SESSION['usuario']['permisos'],$_SESSION['usuario']['codigo_empresa']);
$mostrar = $this->getLista("Mostrar_Historico");
?>
<body>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&;amp;language=es"></script>
    <div id="wrapper">
        <?php include_once("header.php"); ?>
        <?php include_once("bloque-busqueda.php") ?>
        <?php Listados::printListado($mostrar,$this); ?>
    </div>
</body>
