<?php
class ToPdf{
    protected $db=null;
    protected $inmueble=null;
    protected $table=null;
    protected $inmueblesController;
    function __construct($db,$data=null){
        $this->db=$db;
        $this->inmueble=$data;
        $this->inmueblesController=new Inmuebles($db);
    }
    function exec(){
        if(!file_exists(getRoot()."/pdf/".$this->inmueble['referencia'].".pdf")) $this->generarPDF($this->inmueble['referencia']);
        echo getUrl()."/pdf/".$this->inmueble['referencia'].".pdf";
    }
    function generarPDF($referencia){
        include_once(dirname(__FILE__)."/class/html2pdf.class.php");
        $html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8', array(2, 3.5, 2, 3.5));
        $template=file_get_contents(dirname(__FILE__)."/template/ficha.html");
        $html2pdf->writeHTML($this->procesarPlantilla($template));
        $html2pdf->Output(getRoot()."pdf/$referencia.pdf",'F');
    }
    function procesarPlantilla($template){
        $zonas=new Zonas($this->db);
        $tipos=new Tipos($this->db);
        $empresas=new Empresas($this->db);
        $zonas->cargar();
        $tipos->cargar();
        $empresas->cargar();
        $zona=$zonas->buscar('id',$this->inmueble['zona']);
        $tipo=$tipos->buscar('id', $this->inmueble['tipo']);
        $empresa=$empresas->buscar('id',$this->inmueble['id_empresa']);
        $zona=$zona['nombre'];
        $fotoPrincipal=Inmuebles::getFotoPrincipal($this->inmueble['c_inmuebles'], $this->db);
        if(count($fotoPrincipal)==0) $fotoPrincipal['url']=getUrl()."/img/LogoAlia.png";
        $reclamo=Inmuebles::getTextoReclamo($this->inmueble['c_inmuebles'], $this->db);
        $imagenPrincipal=(isset($fotoPrincipal['url']))?"<img width=\"320\" src=\"".$fotoPrincipal['url']."\"/>":"";
        $precio=number_format($this->inmueble['precio_compra'], 0, ',', '.')."€";
        $descripcion=strtolower(nl2br($this->inmueble['descripcion']));
        $pdf=str_replace(array("[Descripcion]","[Zona]","[Tipo]","[FotoPrincipal]","[Precio]","[Referencia]","[Reclamo]","[Datos]","[Empresa]","[Logo]","[Telefono]","[Codigo]"),array($descripcion,$zona,$tipo['nombre'],$imagenPrincipal, $precio, $this->inmueble['referencia'] ,$reclamo,$this->recogerDatos(),$empresa['nombre'],$empresa['logo'],$empresa['telefono'],$empresa['codigo_empresa']),$template);
        return $pdf;
    }
    function recogerDatos(){
        $campos=$this->inmueblesController->getLista("Pdf");
        $html="";
        $i=0;
        foreach($campos['cells'] as $campo){
            $value=$this->inmueblesController->traducirValor($campo[0],$this->inmueble[$campo[0]['key_bd']]);
            if($value==null || $value==-1) continue;
            if($i==0) $html.="<tr style=\"font-size:8pt;\">";

            $html.="<td style=\"font-weight:bold;width:125px;\">".$campo['data']['label_es']."</td>
                    <td style=\"width:60px;text-align:left;\">$value</td>";
            $i++;
            if($i==2){
                $i=0;
                $html.="</tr>";
            }
        }
        if($i<2 && $i!=0){
            $html.="<td></td><td></td></tr>";
        }
        return $html;
    }
}
?>
