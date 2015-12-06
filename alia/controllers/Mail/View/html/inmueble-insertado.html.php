<body>
    <p>La empresa <strong><?php echo $data['empresa']; ?></strong> ha registrado el inmueble <?php echo $data['referencia']; ?></p>
    <p>Puedes ver la ficha <a href="<?php echo getUrl(); ?>/ficha/<?php echo $data['referencia']; ?>">AQUÍ</a></p>
</body>