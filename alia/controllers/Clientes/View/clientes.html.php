<?php
include_once(getRoot() . "/controllers/Base/View/base_admin.html.php");
include_once(getRoot() . "/controllers/Demandas/Controller/demandas.class.php");
include_once(getRoot() . "/controllers/Agentes/Controller/agentes.class.php");
$demandas = new Demandas($this->getDB());
$agentes= new Agentes($this->getDB());
$agentes->cargar();
$demandas->renderViewTemplate();
$this->cargar();
$mostrar = $this->getLista("Mostrar");
$campos = $this->getFormulario("Nuevo");
$modificar = $this->getFormulario("Modificar");
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
        <?php include_once("bloque-busqueda-avanzada.php") ?>
        <?php Listados::printListado($mostrar,$this); ?>
    </div>
</body>