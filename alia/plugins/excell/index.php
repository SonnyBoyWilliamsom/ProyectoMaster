<?php
class ToExcell{
    protected $db=null;
    protected $table=null;
    protected $nombre=null;
    protected $inmuebles=null;
    protected $controller=null;
    function __construct($db,$data=null){
        $this->db=$db;
        $this->table=$data['table'];
        $this->nombre=$data['nombre'];
        $this->inmuebles=$data['datos'];
        $this->controller=$data['controller'];
    }
    function exec(){
        date_default_timezone_set('Europe/Madrid');
        require_once 'Classes/PHPExcel.php';
        $campos=$this->controller->getLista("Excel_Historico");
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Alcorcón Alia")->setLastModifiedBy("Alcorcón Alia")->setTitle("Histórico de Ventas")->setSubject("Histórico de Ventas")->setDescription("Histórico de las ventas realizadas por la Asociación Local de Inmobiliarias de Alcorcón")->setKeywords("inmuebles")->setCategory("Inmuebles");
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1',"Referencia");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1',"Precio");
        for($j=0;$j<count($campos['cells']);$j++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($j,1,$campos['cells'][$j]['data']['label_es']);
        }
        for($i=0;$i<count($this->inmuebles);$i++){
            for($j=0;$j<count($campos['cells']);$j++){
                $value=$this->controller->translateValues($campos['cells'][$j][0]['key_bd'],$this->inmuebles[$i][$campos['cells'][$j][0]['key_bd']]);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($j,($i+2),$value);
            }

        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save(getRoot().'/excel/'.$this->nombre.'.xls');
    }
}
?>
