<form class="fotos" enctype="multipart/form-data" method="post" id="form_Inmuebles_Fotos" action="<?php echo getUrl() ?>/admin/" >
    <?php for ($i = 0; $i < 5; $i++): ?>
        <div class="fotos-inmueble">
            <div class="cuadro">
                <img alt="Imagen" src="" class="imagen"/>
            </div>
            <div class="subir">
                <input type="file" class="input-foto"/>
                <a href="javascript:void(0);" class="descartar" title="Descartar">Descartar</a>
            </div>
            <div class="configuracion">
                <p class="izquierda"><input type="checkbox" class="principal" name="principal[<?php echo $i; ?>]" <?php if($i==0) echo "checked"?> />Principal</p>
                <p class="derecha"><input type="checkbox" class="activa" name="activa[<?php echo $i; ?>]" checked />Activa</p>
            </div>
            <input type="hidden" name="cfoto[<?php echo $i; ?>]" class="cfoto" value=""/>
            <input type="hidden" name="codificada[<?php echo $i; ?>]" class="codificada" value=""/>
            <input type="hidden" name="descartada[<?php echo $i; ?>]" class="descartada" value="0"/>
        </div>
    <?php endfor; ?>
    <input type="hidden" class="cinmueble" name="cinmueble" value="" />
    <input type="hidden" class="query" name="query" value="<?php echo $selectedForm['subindex']; ?>" />
    <input type="hidden" class="controller" name="controller" value="<?php echo $controller->getName() ?>" />
    <input type="submit" name="submit" class="submit" value="Guardar Cambios" />
</form>
<script>
    $(".descartar").click(function (e){
        var descartar=$(e.currentTarget);
        var caja=descartar.parent();
        var contenedor=caja.parent();
        var formulario=contenedor.parent();
        descartar.parent().children("input").val("");
        descartar.parent().parent().children(".cuadro").children("img").attr("src","");
        var checkado=descartar.parent().parent().children(".configuracion").children(".izquierda").children(".principal").prop("checked");
        descartar.parent().parent().children(".configuracion").children(".izquierda").children(".principal").prop("checked",false);
        descartar.parent().parent().children(".configuracion").children(".descripcion").val("");
        descartar.parent().parent().children(".descartada").val("1");
        var copia=contenedor.clone();
        contenedor.remove();
        copia.insertBefore(formulario.children("input").first());
        $(".principal").first().prop("checked",checkado);
    });
    $(".input-foto").change(function(e){
        var imagen=$(e.currentTarget);
        var caja=imagen.parent().parent().children(".cuadro");
        var descartar=caja.parent().children(".descartada");
        var codificada=caja.parent().children(".codificada");
        if(imagen[0].files && imagen[0].files[0]){
            var reader = new FileReader();
            reader.onload = function (e){
                var image = new Image();
                var canvas = document.createElement("canvas");
                image.src = e.target.result;
                image.onload=function(){
                    canvas.width = 640;
                    canvas.height = this.height*canvas.width/this.width;
                    canvas.getContext("2d").drawImage(image, 0, 0, canvas.width, canvas.height);
                    caja.children("img").attr('src', canvas.toDataURL("image/jpeg",0.95));
                    codificada.val(canvas.toDataURL("image/jpeg",0.95));
                }
                descartar.val("0");
            }
            reader.readAsDataURL(imagen[0].files[0]);
        }
    });
    $(".principal").click(function(e){
        var clickado=$(e.currentTarget);
        if(clickado.prop("checked")){
            $(".principal").not(clickado).prop("checked",false);
        }
        else return false;
    });
</script>


