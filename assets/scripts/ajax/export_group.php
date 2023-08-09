<?php
/**
 * Created by   : Ary Herijanto
 * Date         : 10th July 2023
 * Company      : ABC Laboratorium Jakarta
 * Libs         : PHPOFFICE/PHPSPREADSHEET
 *                PHPMAILER  
 *                COMPOSER
 *
 ********************************************/
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);
    date_default_timezone_set('Asia/Jakarta');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');

    require_once '../../../vendor/autoload.php';
    require_once '../../../lib/config.php';
    require_once '../../../lib/header.php'; 

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Style;

    $header1 = $_POST['header1'];
    $header2 = $_POST['header2'];
    $filename1 = $_POST['filename'];
    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    $drawing->setName('ABC Laboratorium');
    $drawing->setDescription('Company Logo');
    $drawing->setPath('../../../logo.png'); /* put your path and image here */
    $drawing->setCoordinates('A1');
    $drawing->setWidthAndHeight(148,74);
    $drawing->setResizeProportional(true);
    //$drawing->setOffsetX(110);
    $drawing->setRotation(0);
    $drawing->getShadow()->setVisible(true);
    $drawing->getShadow()->setDirection(45);
    $drawing->setWorksheet($spreadsheet->getActiveSheet());

    
    foreach(range('B','L') as $columnID) {
        $sheet->getColumnDimension($columnID)
            ->setAutoSize(true);
    }
    $sheet->getStyle("B1")->getFont()->setBold(true);
    $sheet->getStyle("B2")->getFont()->setBold(true);
    
    // $sheet->mergeCells('B1:C1');
    // $sheet->mergeCells('B2:C2');
    // $sheet->getStyle('B1:C1')->getAlignment()->setHorizontal('left');
    // $sheet->getStyle('B2:C2')->getAlignment()->setHorizontal('left');
    $sheet->setCellValue('C1',$header1);
    $sheet->setCellValue('C2',$header2);
    $sheet->getStyle('B:C')->getAlignment()->setHorizontal('center');
    $sheet->getStyle('D')->getAlignment()->setHorizontal('center');
    $sheet->getStyle('F:M')->getAlignment()->setHorizontal('center');
    $sheet->getStyle("A4:M4")->getFont()->setBold(true);
    $sheet->getStyle("C4")->getAlignment()->setHorizontal('center');
    $sheet->setCellValueByColumnAndRow(1,4,'NO');
    $sheet->setCellValueByColumnAndRow(2,4,'ACCESSION NO');
    $sheet->setCellValueByColumnAndRow(3,4,'PATIENT NAME');
    $sheet->setCellValueByColumnAndRow(4,4,'TEST CODE');
    $sheet->setCellValueByColumnAndRow(5,4,'TEST NAME');
    $sheet->setCellValueByColumnAndRow(6,4,'DATE RECEIVED');
    $sheet->setCellValueByColumnAndRow(7,4,'TIME RECEIVED');
    $sheet->setCellValueByColumnAndRow(8,4,'DATE REPORTED');
    $sheet->setCellValueByColumnAndRow(9,4,'TIME REPORTED');
    $sheet->setCellValueByColumnAndRow(10,4,'DATE TAT');
    $sheet->setCellValueByColumnAndRow(11,4,'SCHEDULE');
    $sheet->setCellValueByColumnAndRow(12,4,'RESULT');
    $sheet->setCellValueByColumnAndRow(13,4,'VALIDITY');
    $total_patient=0;
    $state_no=0;
    
    $date_report_array=array();
    $row_array = array();
    
    for($row=0;$row<count($_SESSION['cart_result']);$row++){
            
            $max=0;
            if($row==0){
                $total_patient++;
                $mf = $_SESSION['cart_result'][$row]['accession_no'];
                $daterpt = $_SESSION['cart_result'][$row]['datereported'];
                array_push($date_report_array,$daterpt);
            }else{
                if($_SESSION['cart_result'][$row]["accession_no"] != ' ' ){
                    
                    if($mf==$_SESSION['cart_result'][$row]['accession_no']){
                        array_push($date_report_array,$_SESSION['cart_result'][$row]['datereported']);
                        array_push($row_array,$row);
                        array_push($date_report_array,$daterpt);
                        $unix = array_map('strtotime', $date_report_array);
                        $max = date('d M Y',max($unix));
                    }

                    if($mf != $_SESSION['cart_result'][$row]['accession_no']){
                        $total_patient++;
                        $mf = $_SESSION['cart_result'][$row]['accession_no'];
                        $daterpt = $_SESSION['cart_result'][$row]['datereported'];
                        $date_report_array=array();
                        $row_array=array();
                        array_push($row_array,$row);
                        array_push($date_report_array,$daterpt);
                    }
                }
                
                if($_SESSION['cart_result'][$row]["accession_no"] == ' ' ){
                    array_push($date_report_array,$_SESSION['cart_result'][$row]['datereported']);
                    array_push($row_array,$row);
                    $unix = array_map('strtotime', $date_report_array);
                    $max = date('d M Y',max($unix));
                }
            }
            $sheet->setCellValueByColumnAndRow(1,$row+5, $total_patient);
            $sheet->setCellValueByColumnAndRow(2,$row+5, $_SESSION['cart_result'][$row]['accession_no']);
            $sheet->setCellValueByColumnAndRow(3,$row+5, $_SESSION['cart_result'][$row]['patient_name']);
            $sheet->setCellValueByColumnAndRow(4,$row+5, $_SESSION['cart_result'][$row]['testcode']);
            $sheet->setCellValueByColumnAndRow(5,$row+5, $_SESSION['cart_result'][$row]['testname']);
            $sheet->setCellValueByColumnAndRow(6,$row+5, $_SESSION['cart_result'][$row]['datereceived']);
            $sheet->setCellValueByColumnAndRow(7,$row+5, $_SESSION['cart_result'][$row]['timereceived']);
            
            if($max != 0){
                $rowfirst=$row_array[0];
                $row_last=$row_array[count($row_array)-1];
                $rowfirstadd = $rowfirst+5;
                $rowlastadd = $row_last+5;
               
                $date_merge_text = $max;

                $sheet->mergeCells('A'.$rowfirstadd.':A'.$rowlastadd);
                $sheet->getCell('A'.$rowfirstadd)
                        ->setValue($total_patient);
                $sheet->getStyle('A'.$rowfirstadd)
                        ->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $sheet->mergeCells('B'.$rowfirstadd.':B'.$rowlastadd);
                $sheet->getCell('B'.$rowfirstadd)
                        ->setValue($mf);
                $sheet->getStyle('B'.$rowfirstadd)
                        ->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $sheet->mergeCells('H'.$rowfirstadd.':H'.$rowlastadd);
                $sheet->getCell('H'.$rowfirstadd)
                        ->setValue($max);
                $sheet->getStyle('H'.$rowfirstadd)
                        ->getAlignment()
                        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            }

            if($max==0){
                $sheet->setCellValueByColumnAndRow(8,$row+5, $_SESSION['cart_result'][$row]['datereported']);
            }
            
            $sheet->setCellValueByColumnAndRow(9,$row+5, $_SESSION['cart_result'][$row]['timereported']);
            $sheet->setCellValueByColumnAndRow(10,$row+5, $_SESSION['cart_result'][$row]['datepredict']);
            $sheet->setCellValueByColumnAndRow(11,$row+5, $_SESSION['cart_result'][$row]['schedule']);
            $datereported = date('Y-m-d',strtotime($_SESSION['cart_result'][$row]['datereported']));
            $datepredict = date('Y-m-d',strtotime($_SESSION['cart_result'][$row]['datepredict']));
           
            if($datereported <= $datepredict){
                $datevalid='Yes';
            }else{
                $datevalid='No';
                $state_no++;
            }
            $sheet->setCellValueByColumnAndRow(12,$row+5, $_SESSION['cart_result'][$row]['result']);
            $sheet->setCellValueByColumnAndRow(13,$row+5, $datevalid);
    }
    $maxrow = $sheet->getHighestRow();
    $sheet->setCellValue("A".$maxrow+1, "TOTAL PATIENT :");
    $sheet->setCellValue("D".$maxrow+1, $total_patient);
    $sheet->setCellValue("A".$maxrow+2, "WHSP MELEBIHI TAT : ");
    $sheet->setCellValue("D".$maxrow+2, $state_no);
    $sheet->setCellValue("A".$maxrow+3, "% PENCAPAIAN TAT : ");
    $rowstate = "D".$maxrow+2;
    $rowtotal = "D".$maxrow+1;
    $sheet->setCellValue("D".$maxrow+3,"=(100-((".$rowstate."/".$rowtotal.")*100))");
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('../../../results/'.$filename1.'.xlsx');
    if($writer){
        echo 'true';
    }else{
        echo 'false';
    }
?>