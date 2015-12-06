<?php
$eventos=(isset($datos['eventos']) && count($datos['eventos'])>0)?" ".implode(" ",$datos['eventos']):"";
echo "<form class=\"dos-columnas\" enctype=\"multipart/form-data\" method=\"post\" id=\"form_".$datos['controller']."_".$datos['name']."\" action=\"". getUrl()."/admin/\" $eventos>";
$i=0;
foreach($campos as $campo){
    $eventos=(isset($campo['eventos']) && count($campo['eventos'])>0)?" ".implode(" ",$campo['eventos']):"";
    if($i%2==0) echo "<div class=\"linea\">"; 
    if($campo['tipo']!="hidden" && $campo['tipo']!="oculto"){
        echo "<div class=\"lado\">";
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
        case "radio":
            if($campo['selected']==true) $selected="checked";
            else $selected="";
            echo "<input type=\"".$campo['tipo']."\" name=\"".$campo['key_bd']."\" class=\"".$campo['key_bd']."\" value=\"".$campo['value']."\" $selected $eventos>";
        break;
        case "oculto": break;
        default:
            echo "<input type=\"".$campo['tipo']."\" name=\"".$campo['key_bd']."\" class=\"".$campo['key_bd']."\"  placeholder=\"".strip_tags($campo['label_'.$datos['lang']])."\" ";
            if($campo['obligatorio']) echo "class=\"required\" ";
            echo" $eventos>";
        break;	
    }
    if($campo['tipo']!="hidden" && $campo['tipo']!="oculto"){
        echo "</div>";
        if($i++%2==1) echo "</div>"; 
    }
}
if($i%2==1) echo "</div>"; 
echo "<input type=\"hidden\" class=\"query\" name=\"query\" value=\"".$datos['subindex']."\" />";
echo "<input type=\"hidden\" class=\"controller\" name=\"controller\" value=\"".$datos['controller']."\" />";
echo "<input type=\"submit\" name=\"submit\" class=\"submit\" value=\"Aceptar\" />";
echo "</form>";