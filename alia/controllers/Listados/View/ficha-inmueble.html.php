<div id="datos">
    <?php
    $i=0;
    foreach($lista["cells"] as $campo):
    $label=$campo['data']['label_es'];
    $valor=$this->traducirValor($campo[0],$ficha);
    if(strlen($valor)==0) $valor="-";
    //if(strlen(trim($valor))==0) continue;
    if($i>=30) break;
    ?>
    <div class="dato <?php if(($i++)%5==0) echo "primero"; ?>">
        <p class="nombre"><?php echo $label; ?></p>
        <p class="valor <?php echo $campo[0]['key_bd']; ?>"><?php echo $valor; ?></p>
    </div>
    <?php endforeach; ?>
</div>
