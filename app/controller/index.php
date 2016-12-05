<?php
/**
 * Created by PhpStorm.
 * User: BZ
 * Date: 2016/6/29
 * Time: 19:33
 */

namespace autoload\app\controller;
use autoload\core\controller as A;

class index extends A{
    public function index()
    {
        $this->view['view'] = array(1,2,4);
        //$this->display = null;
        
    }

    public function excel()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Europe/London');

        if (PHP_SAPI == 'cli')
            die('This example should only be run from a Web Browser');

        /** Include PHPExcel */

// Create new PHPExcel object
        $objPHPExcel = new \PHPExcel();

// Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

// Add some data
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Hello')
            ->setCellValue('B2', 'world!')
            ->setCellValue('C1', 'Hello')
            ->setCellValue('D2', 'world!');

// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $time = time();

// Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="01simple'.$time.'.xlsx"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    public function import(){
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $file_name = $_FILES['file']['name'];
            $file = $_FILES['file']['tmp_name'];
            $last = substr(strrchr($file_name, '.'), 1);
            if(is_uploaded_file($file)){
                $f = 'F:/learn/frame/autoload/tmp/'.time().'.'.$last;
                move_uploaded_file($file, $f);
            }
            $result = $this->_import($f,'xlsx');
            print_r($result);die;
        }else{

        }

    }

    /**
     * 导入excel
     * @param $file
     * @param string $excel_type
     * @return array
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     *
     */
    private function _import($file, $excel_type = 'xls')
    {
        if (!file_exists($file))
        {
            return [
                "error" => 0,
                'message' => 'file not found!'
            ];
        }

        if ($excel_type == 'xls')
        {
            $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        }
        else
        {
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        }
        try
        {
            $PHPReader = $objReader->load($file);
        }
        catch (Exception $e)
        {
        }
        if (!isset($PHPReader))
        {
            return [
                "error" => 0,
                'message' => 'read error!'
            ];
        }
        $allWorksheets = $PHPReader->getAllSheets();
        $i = 0;
        foreach ($allWorksheets as $objWorksheet)
        {
            $sheetname = $objWorksheet->getTitle();
            $allRow = $objWorksheet->getHighestRow();//how many rows
            $highestColumn = $objWorksheet->getHighestColumn();//how many columns
            $allColumn = \PHPExcel_Cell::columnIndexFromString($highestColumn);
            $array[$i]["Title"] = $sheetname;
            $array[$i]["Cols"] = $allColumn;
            $array[$i]["Rows"] = $allRow;
            $arr = [];
            $isMergeCell = [];
            foreach ($objWorksheet->getMergeCells() as $cells)
            {//merge cells
                foreach (\PHPExcel_Cell::extractAllCellReferencesInRange($cells) as $cellReference)
                {
                    $isMergeCell[$cellReference] = true;
                }
            }
            for ($currentRow = 1; $currentRow <= $allRow; $currentRow++)
            {
                $row = [];
                for ($currentColumn = 0; $currentColumn < $allColumn; $currentColumn++)
                {
                    ;
                    $cell = $objWorksheet->getCellByColumnAndRow($currentColumn, $currentRow);
                    $afCol = \PHPExcel_Cell::stringFromColumnIndex($currentColumn + 1);
                    $bfCol = \PHPExcel_Cell::stringFromColumnIndex($currentColumn - 1);
                    $col = \PHPExcel_Cell::stringFromColumnIndex($currentColumn);
                    $address = $col . $currentRow;
                    $value = $objWorksheet->getCell($address)->getValue();
                    if (substr($value, 0, 1) == '=')
                    {
                        return [
                            "error" => 0,
                            'message' => 'can not use the formula!'
                        ];
                        exit;
                    }
                    if ($cell->getDataType() == \PHPExcel_Cell_DataType::TYPE_NUMERIC)
                    {
                        $cellstyleformat = $cell->getStyle($cell->getCoordinate())->getNumberFormat();
                        //                      $cellstyleformat=$cell->getParent()->getStyle( $cell->getCoordinate() )->getNumberFormat();
                        //                    	var_dump($cell->getCoordinate());
                        //                    	exit();
                        $formatcode = $cellstyleformat->getFormatCode();
                        if (preg_match('/^([$[A-Z]*-[0-9A-F]*])*[hmsdy]/i', $formatcode))
                        {
                            $value = gmdate("Y-m-d", \PHPExcel_Shared_Date::ExcelToPHP($value));
                        }
                        else
                        {
                            $value = \PHPExcel_Style_NumberFormat::toFormattedString($value, $formatcode);
                        }
                    }
                    if ($isMergeCell[$col . $currentRow] && $isMergeCell[$afCol . $currentRow] && !empty($value))
                    {
                        $temp = $value;
                    }
                    elseif ($isMergeCell[$col . $currentRow] && $isMergeCell[$col . ($currentRow - 1)] && empty($value))
                    {
                        $value = $arr[$currentRow - 1][$currentColumn];
                    }
                    elseif ($isMergeCell[$col . $currentRow] && $isMergeCell[$bfCol . $currentRow] && empty($value))
                    {
                        $value = $temp;
                    }
                    $row[$currentColumn] = $value;
                }
                $arr[$currentRow] = $row;
            }
            $array[$i]["Content"] = $arr;
            $i++;
        }

        unset($objWorksheet);
        unset($PHPReader);
        unset($PHPExcel);
        unlink($file);
        return [
            "error" => 1,
            "data" => $array
        ];
    }
}