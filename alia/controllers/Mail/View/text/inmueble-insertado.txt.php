<?php header("Content-Type:text/html");?>
La empresa <?php echo $data['empresa']; ?> ha registrado el inmueble <?php echo $data['referencia']; ?>.

Puedes ver la ficha en <?php echo getUrl()?>/ficha/<?php echo $data['referencia']; ?>