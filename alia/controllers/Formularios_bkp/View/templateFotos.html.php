<?php
    $eventos=(isset($datos['eventos']) && count($datos['eventos'])>0)?" ".implode(" ",$datos['eventos']):"";
?>
<form class="fotos" enctype="multipart/form-data" method="post" id="form_<?php echo $datos['controller'] . "_" . $datos['name']; ?>" action="<?php echo getUrl() ?>/admin/" <?php echo $eventos; ?> >
    <?php for ($i = 0; $i < 7; $i++): ?>
        <div class="fotos-inmueble">
            <div class="cuadro">
                <img alt="Imagen" src="" class="imagen"/>
            </div>
            <div class="subir">
                <input type="file" name="foto[<?php echo $i; ?>]" class="input-foto"/>
                <a href="javascript:void(0);" class="descartar" title="Descartar">Descartar</a>
            </div>
            <div class="configuracion">
                <p class="izquierda"><input type="checkbox" class="principal" name="principal[<?php echo $i; ?>]" <?php if($i==0) echo "checked"?> />Principal</p>
                <p class="derecha"><input type="checkbox" class="activa" name="activa[<?php echo $i; ?>]" checked />Activa</p>
                <textarea name="descripcion[<?php echo $i; ?>]" class="descripcion" placeholder="Descripción"></textarea>
            </div>
            <input type="hidden" name="cfoto[<?php echo $i; ?>]" class="cfoto" value=""/>
            <input type="hidden" name="descartada[<?php echo $i; ?>]" class="descartada" value="0"/>
        </div>
    <?php endfor; ?>
    <div class="fotos-inmueble plano">
        <div class="cuadro">
            <img alt="Plano" src="" class="imagen"/>
        </div>
        <div class="subir">
            <input type="file" name="plano" class="input-foto"/>
            <a href="javascript:void(0);" class="descartar" title="Descartar">Descartar plano</a>
        </div>
        <input type="hidden" name="plano-code" class="cfoto" value=""/>
        <input type="hidden" name="plano-descartado" class="descartada" value="0"/>
    </div>
    <input type="hidden" class="cinmueble" name="cinmueble" value="" />
    <input type="hidden" class="query" name="query" value="<?php echo $datos['subindex']; ?>" />
    <input type="hidden" class="controller" name="controller" value="<?php echo $datos['controller'] ?>" />
    <input type="submit" name="submit" class="submit" value="Aceptar" />
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
        if(imagen[0].files && imagen[0].files[0]){
            var reader = new FileReader();
            reader.onload = function (e) {
                caja.children("img").attr('src', e.target.result);
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