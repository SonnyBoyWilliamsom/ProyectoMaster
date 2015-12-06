<?php
$uri = trim($_SERVER['REQUEST_URI'],"/");
$uriDesglosaga = explode("/", $uri);
$pagina = $uriDesglosaga[count($uriDesglosaga) - 1];
if($pagina=="admin") $pagina="inmuebles";
if(in_array("zonas",$uriDesglosaga)) $pagina="zonas";
?>
<header>
    <div id="header">
        <h1><a href="<?php echo getUrl(); ?>"></a></h1>
        <nav>
            <ul>
                <li><a href="<?php echo getUrl(); ?>/admin/inmuebles" class="navegacion <?php if($pagina=="inmuebles") echo "active"; ?>">Inmuebles</a></li>
                <li><a href="<?php echo getUrl(); ?>/admin/historico" class="navegacion <?php if($pagina=="historico") echo "active"; ?>">Histórico</a></li>
                <?php if($_SESSION['usuario']['permisos']==1): ?> <li><a href="<?php echo getUrl(); ?>/admin/agentes" class="navegacion <?php if($pagina=="agentes") echo "active"; ?>">Agentes</a></li> <?php endif; ?>
                <?php if($_SESSION['usuario']['permisos']==1): ?> <li><a href="<?php echo getUrl(); ?>/admin/empresas" class="navegacion <?php if($pagina=="empresas") echo "active"; ?>">Empresas</a></li> <?php endif; ?>
                <?php if($_SESSION['usuario']['permisos']==1): ?> <li><a href="<?php echo getUrl(); ?>/admin/zonas" class="navegacion <?php if($pagina=="zonas") echo "active"; ?>">Zonas</a></li> <?php endif; ?>
                <?php if($_SESSION['usuario']['permisos']==1): ?> <li><a href="<?php echo getUrl(); ?>/admin/tipos" class="navegacion <?php if($pagina=="tipos") echo "active"; ?>">Tipos</a></li> <?php endif; ?>
                <?php if($_SESSION['usuario']['permisos']==1): ?> <li><a href="<?php echo getUrl(); ?>/admin/gestiones" class="navegacion <?php if($pagina=="gestiones") echo "active"; ?>">Modelos</a> <?php endif; ?>
                <li><a href="<?php echo getUrl(); ?>/admin/logout" class="navegacion ultimo">Salir</a></li>
            </ul>
        </nav>
    </div>
</header>
