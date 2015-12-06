<?php
include_once(getRoot() . "/controllers/Base/View/base_admin.html.php");
$provincias=new Provincias($this->getDB());
$provincias->cargar();
$provincia=$provincias->buscar("id", $_GET['pr']);
$this->establecerCodigoProvincia($_GET['pr']);
$this->cargar();
$mostrar=$this->getLista("Mostrar_Poblacion");
$campos = $this->getFormulario("Nuevo_Poblacion");
$modificar = $this->getFormulario("Modificar_Poblacion");
?>
<body>
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
        <?php include_once("bloque-busqueda-nuevo.php") ?>
        <?php Listados::printListado($mostrar,$this); ?>
    </div>
</body>