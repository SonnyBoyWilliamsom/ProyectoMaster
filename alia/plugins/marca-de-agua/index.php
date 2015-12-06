<?php
class MarcaDeAgua{
    protected $db=null;
    protected $inmueble=null;
    protected $table=null;
    function __construct($db,$data=null){
        $this->db=$db;
        $this->inmueble=($data!=null)?$data['cinmueble']:null;
        $this->table=$data['table'];
    }
    function exec(){
        $fotos=queryBD("select * from $this->table where tipo=0 and marca=false and c_inmuebles=$this->inmueble",$this->db);
        $marca=  getRoot()."plugins/marca-de-agua/resources/marca.png";
        for($i=0;$i<count($fotos);$i++){
            $foto=$fotos[$i];
            $foto['path']=str_replace(getUrl(),  getRoot(),$foto['url']);
            if(imagejpeg($this->marcadeaguamain($foto['path'], $marca),$foto['path'])) queryBD("update $this->table set marca=true where c_fotos=".$foto['c_fotos'],$this->db);
        }
    }
    function pluginName(){
            return "marca-de-agua";
    }
    function generarFuncion($ext){ 
            return "imagecreatefrom".$ext; 
    }
    function marcadeaguamain($rutaImagen,$marcaAgua,$opacidad=30){
            $imagen=getimagesize($rutaImagen);
            $extencion=$this->getImageExtension($rutaImagen);
            $imagecreate=$this->generarFuncion($extencion);
            $nimagent=$imagecreate($rutaImagen);
            $marca=imagecreatefrompng($marcaAgua);
            $imagen_marca=getimagesize($marcaAgua);
            imagealphablending($marca, true);
            imagesavealpha($marca, true);
            $width_dst=$imagen[0];
            $height_dst=$imagen[1];
            $width_src=$imagen_marca[0];
            $height_src=$imagen_marca[1];
            $left=($width_dst-$width_src)/2;
            $top=($height_dst-$height_src)/2;
            $this->imagecopymerge_alpha($nimagent, $marca, $left, $top , 0, 0, $width_src, $height_src, $opacidad);
            return $nimagent;
    }
    function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){ 
            $cut = imagecreatetruecolor($src_w, $src_h); 
            imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h); 
            imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h); 
            imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct); 
    }
    function getImageExtension($path){
            $trozos=explode(".", $path); 
            $extension = end($trozos);
            if($extension=="jpg") $extension="jpeg";
            return $extension;
    }
}
?>