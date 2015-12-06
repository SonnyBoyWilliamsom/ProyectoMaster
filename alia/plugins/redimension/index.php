<?php
class Redimension{
    protected $db=null;
    protected $inmueble=null;
    protected $table=null;
    const altura=340;
    const anchura=605;

    function __construct($db,$data=null){
        $this->db=$db;
        $this->inmueble=($data!=null)?$data['cinmueble']:null;
        $this->table=$data['table'];
    }
    function exec(){
        $fotos=queryBD("select * from $this->table where tipo=0 and redimensionada=0 and c_inmuebles=$this->inmueble",$this->db);
        for($i=0;$i<count($fotos);$i++){
            $foto=$fotos[$i];
            $foto['path']=str_replace(getUrl(),  getRoot(),$foto['url']);
            if(imagejpeg($this->redimensionar($foto['path']),$foto['path'],100)){
                queryBD("update $this->table set redimensionada=true where c_fotos=".$foto['c_fotos'], $this->db);
            }

        }
    }
    function pluginName(){
        return "redimension";
    }
    function generarFuncion($ext){
        return "imagecreatefrom".$ext;
    }
    function getImageExtension($path){
        $trozos=explode(".", $path);
        $extension = end($trozos);
        if($extension=="jpg") $extension="jpeg";
        return $extension;
    }
    function redimensionar($rutaImagen){
        $extencion=$this->getImageExtension($rutaImagen);
        $imagecreate=$this->generarFuncion($extencion);
        $img_original =$imagecreate($rutaImagen);
        $max_ancho = self::anchura;
        $max_alto = (self::altura/self::anchura)*$max_ancho;
        list($ancho,$alto)=getimagesize($rutaImagen);
        $x_ratio = $max_ancho / $ancho;
        $y_ratio = $max_alto / $alto;
        if( ($ancho <= $max_ancho) && ($alto <= $max_alto) ){
                $ancho_final = $ancho;
                $alto_final = $alto;
        }
        elseif (($x_ratio * $alto) < $max_alto){
                $alto_final = ceil($x_ratio * $alto);
                $ancho_final = $max_ancho;
        }
        else{
                $ancho_final = ceil($y_ratio * $ancho);
                $alto_final = $max_alto;
        }
        $left=(self::anchura-$ancho_final)/2;
        $tmp=imagecreatetruecolor(self::anchura,self::altura);
        $bg_color = imagecolorallocate ($tmp, 95, 14, 10);
        imagefill($tmp, 0, 0, $bg_color);
        imagecopyresampled($tmp,$img_original,$left,0,0,0,$ancho_final, $alto_final,$ancho,$alto);
        return $tmp;
    }
}
?>
