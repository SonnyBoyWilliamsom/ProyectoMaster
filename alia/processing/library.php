<?php
/* Base de Datos */
function connectBD() {
    $root = getRoot();
    if (!$configuracion = parse_ini_file($root . "conf/conf.ini", true))
        die("Se produjo un error en la configuración de acceso a la BBDD");
    $server = $configuracion['BD']['server'];
    $user = $configuracion['BD']['user'];
    $pass = $configuracion['BD']['pass'];
    $database = $configuracion['BD']['database'];
    $port = $configuracion['BD']['port'];
    if(function_exists("mysqli_connect")) $db = mysqli_connect($server, $user, $pass, $database, $port) or die("Se produjo un error conectando con la BBDD");
    else{
        $db = mysql_connect($server.":".$port, $user, $pass) or die("Se produjo un error conectando con la BBDD");
        mysql_select_db($database,$db);
    }
    return $db;
}
function getConfiguration($tag=""){
    if (!$configuracion = parse_ini_file(getRoot() . "conf/conf.ini", true))
        die("Se produjo un error al leer el archivo de configuración");
    if(strlen($tag)==0) return $configuracion;
    else return $configuracion[$tag];
}
function queryBD($query, $db) {
    if(!function_exists("mysqli_connect")) return compatQueryBD ($query, $db);
    else{
        $array = array();
        $consulta = mysqli_query($db, $query) or die(mysqli_error($db));
        if (substr($query, 0, 6) == "select") {
            while ($row = mysqli_fetch_assoc($consulta)) {
                $array[] = $row;
            }
            return $array;
        }
    }
}
function compatQueryBD($query,$db){
    $array = array();
    $consulta = mysql_query($query,$db) or die(mysql_error($db));
    if (substr($query, 0, 6) == "select") {
        while ($row = mysql_fetch_assoc($consulta)) {
            $array[] = $row;
        }
        return $array;
    }
}
function disconnectBD($db) {
    if(function_exists("mysqli_connect")) mysqli_close($db) or die("Se produjo un error en la desconexión a la BD");
    else mysql_close($db) or die("Se produjo un error en la desconexión a la BD");
}

function numberOfRows($query, $db) {
    return count(queryBD($query, $db));
}

function nameOfColumns($table, $db) {
    $aux = queryBD("select * from $table limit 1", $db);
    return array_keys($aux[0]);
}

/* Gestion de la Aplicación */

function getRoot() {
    return dirname(dirname(__FILE__)) . "/";
}

function getUrl() {
    return "http://www.alcorconalia.com";
}

function deleteSpecialChars($string) {
    $string = trim($string);
    $aBuscar = array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä',
        'é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë',
        'í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î',
        'ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô',
        'ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü',
        'ñ', 'Ñ', 'ç', 'Ç');
    $reemplazo = array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A',
        'e', 'e', 'e', 'e', 'E', 'E', 'E', 'E',
        'i', 'i', 'i', 'i', 'I', 'I', 'I', 'I',
        'o', 'o', 'o', 'o', 'O', 'O', 'O', 'O',
        'u', 'u', 'u', 'u', 'U', 'U', 'U', 'U',
        'n', 'N', 'c', 'C');
    $string = str_replace($aBuscar, $reemplazo, $string);
    $string = str_replace(array("\\", "¨", "º", "-", "~",
        "#", "@", "|", "!", "\"",
        "·", "$", "%", "&", "/",
        "(", ")", "?", "'", "¡",
        "¿", "[", "^", "`", "]",
        "+", "}", "{", "¨", "´",
        ">", "< ", ";", ",", ":",
        ".", " "), '', $string);
    return $string;
}
function loadPlugins($pluginName){
    $plugin=queryBD("select * from plugins where nombre=\"$pluginName\"");
    print_r($plugin);
    die();
}
function getLoeadedPlugins() {
    $includes = get_included_files();
    $plugins = array();
    $aBuscar = array(".php", "-");
    $reemplazo = array("", "");
    foreach ($includes as $include) {
        if (strpos($include, "plugins") != false) {
            $path = explode("/", $include);
            $file = $path[count($path) - 1];
            $funcionNombre = str_replace($aBuscar, $reemplazo, $file) . "Name";
            $plugins[] = array("file" => $file, "name" => $funcionNombre());
        }
    }
    return $plugins;
}

function includedFiles() {
    foreach (get_included_files() as $included) {
        $includedFiles[] = strtolower($included);
    }
    return $includedFiles;
}

function formatDate($date) {
    $aux = explode("-", $date);
    return $aux[2] . "/" . $aux[1] . "/" . $aux[0];
}

function getImageExtension($path) {
    $trozos = explode(".", $path);
    $extension = end($trozos);
    if ($extension == "jpg")
        $extension = "jpeg";
    return $extension;
}

function gen_fun_create($ext) {
    return "imagecreatefrom" . $ext;
}
function convertirAJPG($image){
    $extension=strtolower(getImageExtension($image['name']));
    if($extension=="jpg") $extension="jpeg";
    $funcion=  gen_fun_create($extension);
    return $funcion($image['tmp_name']);
}
function ejecutarHooks($hookName,$db,$data=null){
    $plugins=queryBD("select * from plugins where hook=\"$hookName\" and activo=true order by orden asc", $db);
    for($i=0;$i<count($plugins);$i++){
        $plugin=$plugins[$i];
        $nombrePlugin=$plugin['nombre'];
        $clase=$plugin['class'];
        include_once(getRoot()."/plugins/$nombrePlugin/index.php");
        $execPlugin=new $clase($db,$data);
        $execPlugin->exec();
    }
}
function isInAdmin(){
    $splitUri=explode("/",$_SERVER['REQUEST_URI']);
    for($i=0;$i<count($splitUri);$i++){
        if($splitUri[$i]=="admin") return true;
    }
    return false;
}
function getLastsPosts(){
    $server = "localhost";
    $user = "nuevomilenio";
    $pass = "wordpress@nuevo-milenio2015!";
    $database = "blog";
    $port = "3306";
    if(function_exists("mysqli_connect")) $db = mysqli_connect($server, $user, $pass, $database, $port) or die("Se produjo un error conectando con la BBDD");
    else{
        $db = mysql_connect($server.":".$port, $user, $pass) or die("Se produjo un error conectando con la BBDD");
        mysql_select_db($database,$db);
    }
    $posts=queryBD("select post_name,post_content,post_date from nm_posts where post_status='publish' and post_type='post' order by post_date desc limit 10",$db);
    for($i=0;$i<count($posts);$i++){
        $posts[$i]['post_content']=substr(utf8_encode(str_replace(array("\"","'","\n","\r"),"",strip_tags($posts[$i]['post_content']))),0,100);

    }
    return $posts;
}
?>
