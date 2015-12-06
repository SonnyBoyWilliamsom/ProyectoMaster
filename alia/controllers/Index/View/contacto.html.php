<?php
    include_once(getRoot() . "/controllers/Inmuebles/Controller/inmuebles.class.php");
    include_once(getRoot() . "/controllers/Formularios/Controller/formularios.class.php");
    $inmueblesController = new Inmuebles($this->db);
    $buscador=$inmueblesController->getFormulario("Buscador");
?>
<body>
    <?php
    if(!isset($_POST['ref'])): $contacto=$this->getFormulario("Contacto");
    else: $contacto=$this->getFormulario("Mas-Info"); ?>
        <script>
            $(document).ready(function(){
                $("#form_Index_Mas-Info .referencia").val('Referencia: <?php echo $_POST['ref']; ?>');
                $("#form_Index_Mas-Info .referencia").prop("readonly",true);
            });
        </script>
    <?php endif; ?>
    <?php include_once("header.php"); ?>
    <div class="buscadorFlotante" style="display:none;">
        <?php Formularios::printFormulario($buscador,$this->getDB()); ?>
    </div>
    <main>
        <div id="contactoAlia">
            <h2>Contacta con Alia</h2>
            <p>Rellena el siguiente formulario y nos pondremos en contacto contigo lo antes posible</p>
            <div class="formContainer">
                <?php Formularios::printFormulario($contacto,$this->getDB()); ?>
            </div>
        </div>
    </main>
    <?php include_once("footer.php"); ?>
</body>
