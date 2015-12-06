<div id="asesores">
    <?php
    $i=0;
    foreach($lista["cells"] as $campo):
    $label=$campo['data']['label_es'];
    $valor=$campo[0];
    ?>
    <div class="dato <?php if(($i++)%5==0) echo "primero"; ?>">
        <p class="nombre"><?php echo $label; ?></p>
        <p class="valor <?php echo $campo[0]['key_bd']; ?>"><?php echo $valor; ?></p>
    </div>
    <?php endforeach; ?>
</div>
