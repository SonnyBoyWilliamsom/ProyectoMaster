<script>
    var arrayEmpresas= [];
<?php 
    $empresas=$empresasController->obtenerRegistros();
    for($i=0;$i<count($empresas);$i++) :
    ?>
        arrayEmpresas['<?php echo $empresas[$i]['id'] ?>']='<?php echo $empresas[$i]['codigo_empresa']; ?>';
    <?php endfor; ?>
    var procesado=false;
    $(document).ready(function() {
        $('form').validate(true);
        $(".fecha_captacion,.fecha_fin_mandato,.eficiencia_energetica_fecvalid,.fecha_venta,.fecha_llaves").datepicker();
        $("#form_Inmuebles_Nuevo .estado").find(":contains(Disponible)").prop("selected",true);
        $("#form_Inmuebles_Nuevo .provincia").find(":contains(Madrid)").prop("selected",true);
        $("#form_Inmuebles_Nuevo .provincia").change();
        $("#form_Inmuebles_Nuevo .poblacion").find(":contains(Alcorcón)").prop("selected",true);
        $("#form_Inmuebles_Nuevo .poblacion").change();
        $(".nombre_comercializador").change(function (e){
            var caller=$(e.currentTarget);
            obtenerAgente(caller);
        });
        $(".id_agente").val('<?php echo $_SESSION['usuario']['id']?>');
    });
</script>
