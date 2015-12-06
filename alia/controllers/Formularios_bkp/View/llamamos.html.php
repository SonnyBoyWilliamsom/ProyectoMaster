<?php
$eventos=(isset($datos['eventos']) && count($datos['eventos'])>0)?" ".implode(" ",$datos['eventos']):"";
echo "<form class=\"tres-columnas\" enctype=\"multipart/form-data\" method=\"post\" id=\"form_".$datos['controller']."_".$datos['name']."\" $eventos>";
for($i=0;$i<count($campos);$i++){
    $campo=$campos[$i];
    $eventos=(isset($campo['eventos']) && count($campo['eventos'])>0)?" ".implode(" ",$campo['eventos']):"";
    if($campo['tipo']!="hidden" && $campo['tipo']!="oculto"){
        echo "<p class=\"linea\">";
        echo "<label>".$campo['label_'.$datos['lang']]."</label>";
    }
    switch($campo['tipo']){
        case "select":
            echo "<select type=\"".$campo['tipo']."\" name=\"".$campo['key_bd']."\" class=\"".$campo['key_bd']."\"";
            if($campo['obligatorio']) echo "class=\"required\" ";
            echo" $eventos>";
            if(isset($campo['opciones'])){
                foreach($campo['opciones'] as $opcion){
                    echo "<option value=\"".$opcion['valor']."\">".$opcion['opcion']."</option>";	
                }
            }
            echo "</select>";				
        break;
        case "textarea":
            echo "<textarea name=\"".$campo['key_bd']."\" class=\"".$campo['key_bd']."\" placeholder=\"". strip_tags($campo['label_'.$datos['lang']]) ."\" $eventos></textarea>";
        break;
        case "hidden":
            echo "<input type=\"".$campo['tipo']."\" name=\"".$campo['key_bd']."\" class=\"".$campo['key_bd']."\" $eventos>";
        break;
        case "oculto": break;
        case "slide":
            echo "<input type=\"hidden\" name=\"".$campo['key_bd']."\" class=\"".$campo['key_bd']."\" $eventos>";
            echo "<div id=\"slider-".$campo['key_bd']."\"></div>
            <script>
                $(\"#slider-".$campo['key_bd']."\").slider({
                        value: 0,
                        min: ".$campo['param']['min'].",
                        max: ".$campo['param']['max'].",
                        step: 1,
                        values: [".$campo['param']['min'].", ".$campo['param']['max']."],
                        change: function( event, ui ){
                            $(\"#slider-".$campo['key_bd']."\").parent().children(\".linea\").children(\".".$campo['key_bd']."\").val(ui.values[0]+\"-\"+ui.values[1]);
                        }
                    }).each(function() {
                    var vals = ".$campo['param']['max']." - ".$campo['param']['min'].";
                    for (var i = 0; i <= vals; i+=1) {
                        var el = $('<label>'+(i+".$campo['param']['min'].")+'</label>').css('left',(i/vals*100)+'%');
                        if(i%".$campo["param"]["step"]."!=0) el.hide();
                        $(\"#slider-".$campo['key_bd']."\").append(el);
                    }
                });
            </script>";
        break;
        case "radio":
            if($campo['selected']==true) $selected="checked";
            else $selected="";
            echo "<input type=\"".$campo['tipo']."\" name=\"".$campo['key_bd']."\" class=\"".$campo['key_bd']."\" value=\"".$campo['value']."\" $selected $eventos>";
        break;
        default:
            echo "<input type=\"".$campo['tipo']."\" name=\"".$campo['key_bd']."\" class=\"".$campo['key_bd']."\"  placeholder=\"".strip_tags($campo['label_'.$datos['lang']])."\" ";
            if($campo['obligatorio']) echo "class=\"required\" ";
            echo" $eventos>";
        break;	
    }
    if($i+1==count($campos))
        echo "<input type=\"submit\" name=\"submit\" class=\"submit\" value=\"LLAMENME\" />";
    if($campo['tipo']!="hidden" && $campo['tipo']!="oculto"){
        echo "</p>";
    }
}
echo "<input type=\"hidden\" class=\"query\" name=\"query\" value=\"".$datos['subindex']."\" />";
echo "<input type=\"hidden\" class=\"controller\" name=\"controller\" value=\"".$datos['controller']."\" />";
echo "</form>";