<?php
    include_once(getRoot()."/controllers/Base/View/base_admin.html.php");
    $this->cargar();
    $campos=$this->getFormulario("Login");
?>
<body style="overflow:hidden">
    <script>
        function colocarLogin(){
            $("#form_Agentes_Login").css("margin-top",0);
            var altoFormulario=$("#form_Agentes_Login").height()+30;
            var altoVentana=$(window).height();
            var mt=(altoVentana-altoFormulario)/2;
            $("#form_Agentes_Login").css("margin-top",mt);
        }
        $(document).ready(function(){setTimeout(colocarLogin,100);});
        $(window).resize(colocarLogin)
    </script>
    <?php //Formularios::printFormulario($campos,"templateLogin"); ?>
    <?php Formularios::printFormulario($campos); ?>
</body>