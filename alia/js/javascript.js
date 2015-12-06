jQuery(function($){
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '&#x3c;Ant',
        nextText: 'Sig&#x3e;',
        currentText: 'Hoy',
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
        dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
        dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: '' };
    $.datepicker.setDefaults($.datepicker.regional['es']);
});
var direccion = "http://www.alcorconalia.com";
String.prototype.replaceAt=function(index, character) {
    return this.substr(0, index) + character + this.substr(index+character.length);
}
function realizarBusquedaEnTabla(identifier, valor) {
    var tabla = $("table" + identifier + " tbody");
    var filas = tabla.children().not(tabla.children().first());
    filas.each(function(entry, value) {
        var fila = $(value);
        var celdas = fila.children();
        var encontrado = false;
        celdas.each(function(entry, cell) {
            var celda = $(cell);
            var contenido = celda.html().toLowerCase();
            if (contenido.indexOf(valor.toLowerCase()) != -1)
                encontrado = true;
        });
        if (encontrado)
            fila.show();
        else
            fila.hide();
    });
}

function eliminar(controller, index, id, row) {
    if (confirm('¿Estas seguro de que quieres eliminar este elemento?')) {
        $.ajax({
            url: direccion + "/admin/",
            type: "POST",
            data: "ajax=true&query=" + index + "&id=" + id + "&controller=" + controller,
            success: function(source) {
                if (source == 0) {
                    row.hide();
                    row.remove();
                }
            }
        });
    }
}
function obtenerAgente(caller){
    $.ajax({
        url: direccion + "/admin/",
        type: "POST",
        dataType:"json",
        data: "ajax=true&query=7&id=" + caller.val() + "&controller=Agentes",
        success: function(source) {
            var finder=caller.parent().parent().parent();
            finder.find(".telefono_comercializador").val(source.tlf);
            finder.find(".email_comercializador").val(source.mail);
        }
    });
}
function eliminarVarios(controller, index, id) {
    var seleccionados = $(id.toLowerCase() + " input[type=checkbox]:checked");
    if (seleccionados.length == 0)
        return false;
    var post = {ajax: true, query: index, controller: controller};
    post['id'] = new Array();
    seleccionados.each(function(key, element) {
        var elemento = $(element);
        post['id'].push(elemento.attr('class'));
    });
    $.ajax({
        url: direccion + "/admin/",
        type: "POST",
        data: post,
        success: function(source) {
            if (source == "0") {
                seleccionados.parent().parent().hide();
                seleccionados.parent().parent().remove();
            }
        }
    });
}
function elegirFormato(id, value) {
    var hipervinculo = $(id).attr("href").replace(/format=(.*)/, "format=" + value);
    $(id).attr("href", hipervinculo);
}
function cambiarCampoExportacion(id, object) {
    var selects = $(id).not(object);
    var value = $(object).val();
    for (var i = 0; i < selects.length; i++) {
        var selector = $($(selects)[i]);
        if (selector.val() == value) {
            selector.children().first().attr("selected", true);
        }
    }
}
function importar(id, controller, index) {
    var tabla = $(id);
    var filas = tabla.children().not(tabla.children().first());
    var primeraFila = $(id + " select");
    var header = [];
    primeraFila.each(function(key, value) {
        var input = $(value);
        header.push(input.val());
    });
    for (var i = 0; i < filas.length; i++) {
        var datos = {ajax: true, query: index};
        var celdas = $($(filas)[i]).children().not(".resultado");
        var check = celdas.first().children("input[type=checkbox]");
        var correcto = true;
        if (check.prop("checked") == false)
            continue;
        celdas = celdas.not(celdas.first());
        for (var j = 0; j < celdas.length; j++) {
            if (header[j] == -1)
                continue;
            datos[header[j]] = $($(celdas)[j]).html();
        }
        ajaxImportar($($(filas)[i]), datos, controller);
    }
}
function ajaxImportar(fila, datos, controller) {
    $.ajax({
        url: direccion + "/admin/",
        type: "POST",
        data: datos,
        success: function(source) {
            if (fila.children(".resultado").length == 0) {
                var td = $(document.createElement("td"));
                td.addClass("resultado");
                fila.append(td);
            }
            else {
                var td = fila.children(".resultado");
            }
            if (!source) {//Repetido
                td.html("Registro repetido");
                fila.css("background-color", "#FFBF00");
            }
            else if (source) {//Ok
                td.html("Correcto");
                fila.css("background-color", "#ACFA58");
            }
            else {//Error
                td.html("Registro repetido");
                fila.css("background-color", "#F78181");
            }
        }
    });
}
function editar(e) {
    var celda = $(e.currentTarget);
    celda.unbind("click");
    var input = $(document.createElement("input"));
    input.val(celda.html());
    input.blur(finalizar).keyup(comprobar);
    celda.html("").append(input);
    input.focus();
}
function comprobar(e) {
    if (e.keyCode == 13) {
        var celda = $(e.target).parent();
        celda.click(editar);
        var input = celda.children("input");
        celda.html(input.val());
        input.remove();
    }
    else {
        $(e.target).parent().parent().addClass("modificado");
    }
}
function finalizar(e) {
    var celda = $(e.currentTarget).parent();
    celda.click(editar);
    var input = celda.children("input");
    celda.html(input.val());
    input.remove();
}
function cambiarFormulario(identificador) {
    var filas = $("#" + identificador + " tbody").children("tr.modificado").children("td").parent();
    filas.removeClass("modificado");
    filas.each(function(key, value) {
        var celdas = $(value).children("td").not(".eliminar").not(".options");
        var idCampo = $(value).attr("class");
        var datos = {};
        if (idCampo == 0) {
            datos['query'] = 1;
            datos['id'] = identificador;
        }
        else
            datos['query'] = 0;
        datos['ajax'] = true;
        datos['id_campo'] = idCampo;
        datos['controller'] = "Formularios";
        celdas.each(function(key, value) {
            var celda = $(value);
            var llave = celda.removeClass("editable").attr('class');
            var valor;
            if (celda.children().length > 0) {
                if (celda.children().attr("type") == "checkbox")
                    valor = celda.children().prop("checked");
                else
                    valor = celda.children().val();
            }
            else
                valor = celda.html();
            datos[llave] = valor;
        });
        $.ajax({
            url: "../admin/",
            type: "POST",
            data: datos,
            success: function(source) {
            }
        });
    });
}
function modificarSelect(selector) {
    var idCampo = selector.parent().parent().removeClass("modificado").attr("class");
    var fila = selector.parent().parent();
    selector.parent().parent().addClass("modificado");
    if (selector.val() == "select") {
        selector.parent().parent().css("background-color", "orange");
        var celda = $(document.createElement("td"));
        celda.addClass("options " + idCampo);
        var opciones = $(document.createElement("input"));
        opciones.attr("placeholder", "Introduce las posibles opciones separadas por comas");
        selector.parent().parent().append(celda.append(opciones));
    }
    else if (fila.children("td.options." + idCampo).length > 0) {
        fila.children("td.options." + idCampo).remove();
        selector.parent().parent().css("background-color", "");
    }
}
function modificarChk(selector) {
    selector.parent().parent().addClass("modificado");
}
function agregarCampo(identificador) {
    var tabla = $("#" + identificador + " tbody");
    var fila = tabla.children().last().clone();
    fila.removeClass(fila.attr("class")).addClass("0");
    fila.children().each(function(key, value) {
        var celda = $(value);
        if (celda.children().length == 0)
            celda.html("");
        else {
            if (celda.children().prop("checked"))
                celda.children().prop("checked", false);
            else
                celda.children().val("");
        }
    });
    fila.children(".editable").not(".key_bd").click(editar);
    tabla.append(fila);
}
function verDemandas(id) {
    var controller = "demandas";
    $.ajax({
        url: direccion + "/admin/",
        dataType: "json",
        type: 'POST',
        async: true,
        data: "query=0&ajax=true&id_cliente=" + id,
        success: function(source) {
        }
    });
}
function rellenarCampo(identificador, valor) {
    identificador.val(valor);
}
function cargarPoblaciones(id, destiny, query) {
    $.ajax({
        url: direccion + "/admin/",
        type: "POST",
        async: false,
        data: "query=" + query + "&controller=Poblaciones&ajax=true&codigo_sup=" + id,
        dataType: 'json',
        success: function(data) {
            $(destiny).children().remove();
            var option = document.createElement("option");
            $(option).val("-1").html("");
            $(destiny).append(option);
            for (var i = 0; i < data.length; i++) {
                var option = document.createElement("option");
                $(option).val(data[i].id).html(data[i].nombre);
                $(destiny).append(option);
            }
        }
    });
}
function cargarZonas(id, destiny, query) {
    $.ajax({
        url: direccion + "/admin/",
        type: "POST",
        async: false,
        data: "query=" + query + "&controller=Zonas&ajax=true&codigo_sup=" + id,
        dataType: 'json',
        success: function(data) {
            $(destiny).children().remove();
            var option = document.createElement("option");
            $(option).val("-1").html("");
            $(destiny).append(option);
            for (var i = 0; i < data.length; i++) {
                var option = document.createElement("option");
                $(option).val(data[i].id).html(data[i].nombre);
                $(destiny).append(option);
            }
        }
    });
}
function modificar(id, controller, query, nombreForm, cprovincia, czona) {
    $.ajax({
        url: direccion + "/admin/",
        dataType: "json",
        type: 'POST',
        async: true,
        data: "query=" + query + "&controller=" + controller + "&ajax=true&id=" + id,
        success: function(source) {
            console.log(source);
            $.map(source, function(value, index) {
                var input = $("#form_" + controller + "_" + nombreForm + " ." + index);

                if (input.attr("type") == "select") {
                    if (input.attr("name") == "c_provincia" || input.attr("name") == "provincia") {
                        cargarPoblaciones(value, "select.c_poblacion,select.poblacion", cprovincia);
                    }
                    if (input.attr("name") == "c_poblacion" || input.attr("name") == "poblacion") {
                        cargarZonas(value, "select.c_zona,select.zona", czona);
                    }
                    input.children().prop("selected", false);
                    input.children().each(function(key, valor) {
                        var option = $(valor);
                        if (option.attr("value") == value)
                            option.prop("selected", true);
                    });
                }
                else if (input.attr("type") == "checkbox") {
                    var checked = false;
                    if (value == 1)
                        checked = true;
                    input.prop("checked", checked);
                }
                else if (input.attr("type") == "file") {
                    var enlace=input.next("a.ver-"+input.attr('class'));
                    enlace.attr("href",value);
                }
                else
                    input.prop("value", value);
            });
            desplegar("#form_" + controller + "_" + nombreForm);
        },
        error: function(xhr, status, error) {

        }
    });
}
function verDemandas(id, controller, query) {
    $.ajax({
        url: direccion + "/admin/",
        dataType: "json",
        type: 'POST',
        async: true,
        data: "query=" + query + "&controller=" + controller + "&ajax=true&id=" + id,
        success: function(source) {
            $("#demanda").remove();
            $("#demanda-tpl").tmpl(source).appendTo("body");
        }
    });
}
function desplegar(selector) {
    var formulario = $(selector);
    var box = formulario.parent(".plegado");
    var separacionV = $(window).scrollTop() + 20;
    var separacionH = ($(window).width() - formulario.width()) / 2;
    if ($(".sombra").length == 0) {
        var div = $(document.createElement("div"));
        div.addClass("sombra");
        $("body").append(div);
    }
    var cerrar = $(document.createElement("a"));
    cerrar.addClass("fa-times");
    cerrar.attr("onclick", "javascript:replegar('" + selector + "');").attr("id", "cerrar").attr("href", "javascript:void(0);");
    box.append(cerrar);
    box.css({"top":"50%","left":"50%","padding":"10px","overflow":"visible","width":"0","height":"0"});
    box.animate({"height": formulario.height(), "width": formulario.width(), "left": separacionH, "top": separacionV},
        function(){
            box.css("height","auto");
        });
    $(".sombra").css({"left":"0px","height": "100%", "width": "100%","top":"0px","opacity":"0"}).show();
    $(".sombra").animate({"opacity":"1"});
}
function replegar(selector) {
    $("#cerrar").remove();
    $("#cerrar").css("display:none");
    var formulario = $(selector);
    var box = formulario.parent(".plegado");

    box.css("padding", "0px").css("overflow", "hidden");
    box.animate({"height": 0, "width": 0, "top": "50%", "left": "50%"});
    $(".sombra").animate({"opacity":"0"},function(){$(".sombra").hide();});
}
function actualizarFotos(query,codigo, controlador, selector,modificar,fotos) {
    var formulario = $("#form_" + controlador + "_" + selector);
    formulario.parent().css("width", "90%");
    formulario.children(".cinmueble").val(codigo);
    $.ajax({
        url: direccion + "/admin/",
        dataType: "json",
        type: 'POST',
        async: true,
        data: "query=" + query + "&controller=" + controlador + "&ajax=true&id=" + codigo,
        success: function(source) {
            for(var i=0;i<5;i++){
                var datos=(source.fotos[i])?source.fotos[i]:false;
                var caja=$(formulario.children()[i]);
                var url=(datos)?datos.url:"";
                var principal=(datos)?datos.principal:((i==0)?"1":"0");
                var activo=(datos)?datos.activo_01:"1";
                var descripcion=(datos)?datos.descripcion:"";
                var id=(datos)?datos.c_fotos:"";
                caja.children(".cuadro").children("img").attr("src",url);
                caja.children(".configuracion").children(".izquierda").children("input").prop("checked",principal=="1");
                caja.children(".configuracion").children(".derecha").children("input").prop("checked",activo=="1");
                caja.children(".configuracion").children("textarea").attr("value",descripcion);
                caja.children(".cfoto").attr("value",id);
            }
            if(source.fotos[0]) caja.parent().children(".query").val(modificar);
            else  caja.parent().children(".query").val(fotos);
        }
    });
    desplegar("#form_" + controlador + "_" + selector);
}
function validaFormu(object){
    return true;
}
function cargarPresupuestos(valor,destino,controlador,query){
    destino.children().remove();
    $.ajax({
        url: direccion + "/admin/",
        dataType: "json",
        type: 'POST',
        async: true,
        data: "query=" + query + "&controller=" + controlador + "&ajax=true&valor=" + valor,
        success: function(source) {
            for(var i=0;i<source.length;i++){
                var option=$(document.createElement("option"));
                option.val(source[i]);
                if(i==0) option.val("-1");
                if(i==source.length-1) option.html(source[i]+"+");
                else option.html(source[i]);
                destino.append(option);
            }
        }
    });
}
function busquedaAvanzada(form,table){
    var campos=$(form).find("input,select,textarea").not("[type=submit]").not("[type=reset]");
    var posts=new Array();
    for(var i=0;i<campos.length;i++){
        var campo=$(campos[i]);
        var valor;
        if(campo.val()!=undefined && (campo.val()!=null && campo.val()!=-1) && campo.val().length>0){
            if(campo.attr("type")=="checkbox") valor=campo.prop("checked");
            else valor=campo.val();
            posts[posts.length]=campo.attr("name")+"="+valor;
        }
    }
    var query=posts.join("&");
    $(table+"[id]").show();
    $.ajax({
        url: direccion + "/admin/",
        dataType: "json",
        type: 'POST',
        async: true,
        data:query,
        success: function(source){
            replegar("#"+$(form).attr("id"));
            $(table+" tr[id]").hide();
            $.map(source,function(value,index){
                $(table+" tr#"+value.id).show();
            });
        }
    });
    return false;
}
function deshacerBusqueda(form,table){
    replegar("#"+$(form).attr("id"));
    $(table+" tr[id]").show();
}
function cambioDeCategoria(object){
    var jObject=$(object);
    var jForm=jObject.parent().parent().parent().parent().parent();
    var visible=jForm.find(".visible").parent();
    var opciones=jForm.find(".opciones_inactivo").parent();
    if(object.value==0){
        visible.show();
        opciones.hide();
    }
    else if(object.value==1){
        opciones.show();
        $("[data-validation]").removeAttr('data-validation');
        visible.hide();
    }
    else{
        opciones.hide();
        visible.hide();
    }
    actualizarReferencia(jForm);
}
var rtime = new Date(1, 1, 2000, 12,00,00);
var timeout = false;
var delta = 200;
function ampliarPlano(selector){
    var jPlano=$(selector);
    var jPlanoAmpliado=$("#plano-ampliado");
    var plano=jPlano.find("img").clone();
    jPlanoAmpliado.children().children().not(".cerrar").remove();
    jPlanoAmpliado.children().append(plano);
    jPlanoAmpliado.show();
    $("body").css("overflow","hidden");
    $("#sombra").show();
    colocarPlanoAmpliado(jPlanoAmpliado);
    $(window).resize(function(){
        rtime = new Date();
        if (timeout === false) {
            timeout = true;
            setTimeout(function(){
                colocarPlanoAmpliado(jPlanoAmpliado);
            }, delta);
        }
    });
}
function colocarPlanoAmpliado(jPlanoAmpliado){
    if (new Date() - rtime < delta) {
        setTimeout(function(){
            colocarPlanoAmpliado(jPlanoAmpliado);
        }, delta);
    }
    else{
        timeout = false;
        var anchoVentana=window.innerWidth;
        var altoVentana=window.innerHeight;
        var anchoCaja=jPlanoAmpliado.children().width();
        var altoCaja=jPlanoAmpliado.children().height();
        var cambioAncho=false;
        if(anchoVentana<anchoCaja){
            var relacion=altoCaja/anchoCaja;
            jPlanoAmpliado.children().css("width",anchoVentana-50);
            var alto=relacion*(anchoVentana-50);
            jPlanoAmpliado.children().css("height",alto);
            cambioAncho=true;
            anchoCaja=(anchoVentana-50);
            altoCaja=alto;
        }
        else jPlanoAmpliado.children().css("width","");
        if(altoVentana<altoCaja){
            var relacion=anchoCaja/altoCaja;
            jPlanoAmpliado.children().height(altoVentana-50);
            altoCaja=altoVentana-50;
            anchoCaja=relacion*altoCaja;
        }
        else if(!cambioAncho) jPlanoAmpliado.children().css("height","");
        var jTop=Math.max(0,(altoVentana-altoCaja)/2);
        var jLeft=Math.max(0,(anchoVentana-anchoCaja)/2);
        jPlanoAmpliado.animate({"left":jLeft,"top":jTop});
    }
}
function cerrarPlano(){
    $("#sombra").hide();
    $("body").css("overflow","");
    $("#plano-ampliado").hide();
}
function obtenerSoloNumeros(cadena){
    return cadena.replace(/[^\d]/g, '');
}
function obtenerSoloLetras(cadena){
    return cadena.replace(/[\d]/g, '');
}
function actualizarReferencia(form,empresa){
    var referencia=form.find(".referencia");
    var referenciaValor=referencia.val().substring(2);
    var prefijo=arrayEmpresas[empresa];
    referencia.val(prefijo+referenciaValor);
}
function cambioEmpresa(selector){
    var jForm=$(selector).parent().parent().parent().parent().parent();
    actualizarReferencia(jForm,$(selector).val());
}
function insercionDeInmueble(formulario,options){
    var jForm=$(formulario);
    var idCliente=jForm.find(".id_cliente").val();
    if(idCliente.length==0 || isNaN(idCliente)){
        if (confirm('Este inmueble no tiene ningún cliente asociado, ¿Desea asociar uno ahora?')) {
            var formularioClientes=$("#form_clientes_Insercion_de_inmuebles");
            jForm.parent().hide();
            desplegar('#form_clientes_Insercion_de_inmuebles');
            jForm.unbind("submit");
            return false;
        }
    }
    return true;
}
function asociarClienteAInmueble(selector){
    $(selector).parent().show();
    $('#form_clientes_Insercion_de_inmuebles').parent().hide();
    return false;
}
function crearNuevoClienteAjax(formulario,selector){
    var data=$(formulario).serialize()+"&ajax=true";
    $.ajax({
        url: direccion + "/admin/",
        type: 'POST',
        async: true,
        data: data,
        success: function(source){
            $(selector).parent().show();
            $('#form_clientes_Insercion_de_inmuebles').parent().hide();
            $("#form_Inmuebles_Nuevo .id_cliente").val(source);
        }
    });
    return false;
}
function paginaSiguiente(object,pagina){
    var jPageForm=$(object);
    jPageForm.children(".paginado.activo").removeClass("activo");
    jPageForm.children(".paginado."+pagina).addClass("activo");
}
function paginaAnterior(object,pagina){
    var jPageForm=$(object);
    jPageForm.children(".paginado.activo").removeClass("activo");
    jPageForm.children(".paginado."+pagina).addClass("activo");
}
function comparar(controller,ajaxIndex,id,object){
    $.ajax({
        url: direccion +"/admin/",
        type: 'POST',
        dataType: 'json',
        data: "ajax=true&query="+ajaxIndex+"&id="+id+"&controller="+controller,
        success: function(source){
            if(source.redirect==true) location.href=direccion+"/comparar/";
            $(object).hide();
        }
    });
}
function desplegarBuscador(){
    var buscador=$("#buscador");
    if(buscador.hasClass("plegado")) buscador.removeClass("plegado");
    else buscador.addClass("plegado");
}

function ordenarPrecio(object,idQuery,controllerName){
    var tabla=$("#resultados table");
    var filas=$("#resultados table tr[id]");
    var jObject=$(object);
    var ids=Array();
    for(var i=0;i<filas.length;i++){
        ids.push($(filas[i]).attr("id"));
    }
    var order=(jObject.data("order"))?jObject.data("order"):"asc";
    if(order=="asc") jObject.data("order","desc");
    else jObject.data("order","asc");
    var query="ids[]="+ids.join("&ids[]=")+"&ajax=true&query="+idQuery+"&controller="+controllerName+"&order="+order;
    $.ajax({
        url: direccion +"/admin/",
        type: 'POST',
        dataType: 'json',
        data: query,
        success: function(source){
            for(var i=0;i<source.length;i++){
                var codigo=source[i].c_inmuebles;
                var fila=$("#resultados table #"+codigo);
                $("#resultados table").append(fila);
            }

        }
    });
}
function ordenarZona(object,idQuery,controllerName){
    var tabla=$("#resultados table");
    var filas=$("#resultados table tr[id]");
    var jObject=$(object);
    var ids=Array();
    for(var i=0;i<filas.length;i++){
        ids.push($(filas[i]).attr("id"));
    }
    var order=(jObject.data("order"))?jObject.data("order"):"asc";
    if(order=="asc") jObject.data("order","desc");
    else jObject.data("order","asc");
    var query="ids[]="+ids.join("&ids[]=")+"&ajax=true&query="+idQuery+"&controller="+controllerName+"&order="+order;
    $.ajax({
        url: direccion +"/admin/",
        type: 'POST',
        dataType: 'json',
        data: query,
        success: function(source){
            for(var i=0;i<source.length;i++){
                var codigo=source[i].c_inmuebles;
                var fila=$("#resultados table #"+codigo);
                $("#resultados table").append(fila);
            }
        }
    });
}
function ordenarEstado(object,idQuery,controllerName){
    var tabla=$("#resultados table");
    var filas=$("#resultados table tr[id]");
    var jObject=$(object);
    var ids=Array();
    for(var i=0;i<filas.length;i++){
        ids.push($(filas[i]).attr("id"));
    }
    var order=(jObject.data("order"))?jObject.data("order"):"asc";
    if(order=="asc") jObject.data("order","desc");
    else jObject.data("order","asc");
    var query="ids[]="+ids.join("&ids[]=")+"&ajax=true&query="+idQuery+"&controller="+controllerName+"&order="+order;
    $.ajax({
        url: direccion +"/admin/",
        type: 'POST',
        dataType: 'json',
        data: query,
        success: function(source){
            for(var i=0;i<source.length;i++){
                var codigo=source[i].c_inmuebles;
                var fila=$("#resultados table #"+codigo);
                $("#resultados table").append(fila);
            }
        }
    });
}
function vendido(id,idForm){
    desplegar(idForm);
    $(idForm+" .c_inmuebles").val(id);
}
function mostrarFormulario(classSelector){
    var capa=$(classSelector);
    capa.show();
    var formulario=capa.children("form");
    var superior=$(window).height()-formulario.height();
    console.log(formulario);

    formulario.css("margin-top",superior/2);
    capa.click(function(e){
        if(e.target==e.currentTarget) capa.hide();
    });
}
function asociadoDelMes(controllerName,idQuery,idAsociado){
    var query="ajax=true&query="+idQuery+"&controller="+controllerName+"&id="+idAsociado;
    $.ajax({
        url: direccion +"/admin/",
        type: 'POST',
        data: query,
        success: function(source){}
    });
}
function filtroPorEmpresa(idTabla,empresa){
    $(idTabla+" tr td").parent().hide();
    if(empresa!=-1) $(idTabla+" tr td:contains("+empresa+")").parent().show();
    else $(idTabla+" tr td").parent().show();
}
