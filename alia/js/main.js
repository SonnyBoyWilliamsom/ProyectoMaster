function abrirFormulario(){
    $("#formulario").animate({"width":$("#formulario").children("div").width()},function(){
        $(".cerrar-form").html("Cerrar").attr('onclick','javascript:cerrarFormulario();');
    });
}
function cerrarFormulario(){
    $("#formulario").animate({"width":"26px"},function(){
        $(".cerrar-form").html("M&aacute;s informaci&oacute;n").attr('onclick','javascript:abrirFormulario();');
    });
}
function centrarCajaSlider(){
    var cajas=$("#sliderMain .slider .auxiliar");
    var navegacion=$("header #header ul li").last();
    var izquierdaOffset=parseInt($("#sliderMain .slider").css("margin-left"));
    var izquierda=parseInt(navegacion.position()['left'])-izquierdaOffset-cajas.width();
    cajas.animate({'left':izquierda});
}
function centrarTitulos(){
    var titulos=$(".titulo-index");
    var anchoVentana=$(window).width();
    var margen=Math.max(100+(anchoVentana-960)/2,100);
    titulos.css("margin-left",margen);
}
function resizeend() {
    if (new Date() - rtime < delta) {
        setTimeout(resizeend, delta);
    } else {
        timeout = false;
        centrarCajaSlider();
    }
}
function cambio(check,selector){
    $(selector).prop("disabled",!check);
}
