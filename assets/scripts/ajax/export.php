<?php
/**
 * Created by   : Ary Herijanto
 * Date         : 10th July 2023
 * Company      : ABC Laboratorium Jakarta
 * Libs         : PHPOFFICE/PHPSPREADSHEET
 *                PHPMAILER  
 *                COMPOSER
 *
 **/
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

    $header1 = $_POST['header1'];
    $header2 = $_POST['header2'];
    $filename1 = $_POST['filename'];
    
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    foreach(range('A','L') as $columnID) {
        $sheet->getColumnDimension($columnID)
            ->setAutoSize(true);
    }
    $sheet->getStyle("A1")->getFont()->setBold(true);
    $sheet->getStyle("A2")->getFont()->setBold(true);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal('left');
    $sheet->getStyle('A2')->getAlignment()->setHorizontal('left');
    $sheet->mergeCells('A1:B1');
    $sheet->mergeCells('A2:B2');
    $sheet->setCellValue('A1',$header1);
    $sheet->setCellValue('A2',$header2);
    $sheet->getStyle('A:B')->getAlignment()->setHorizontal('center');
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
    $sheet->setCellValueByColumnAndRow(12,4,'VALIDITY');

    for($row=0;$row<count($_SESSION['cart_result']);$row++){
            $sheet->setCellValueByColumnAndRow(1,$row+5, $_SESSION['cart_result'][$row]['no']);
            $sheet->setCellValueByColumnAndRow(2,$row+5, $_SESSION['cart_result'][$row]['accession_no']);
            $sheet->setCellValueByColumnAndRow(3,$row+5, $_SESSION['cart_result'][$row]['patient_name']);
            $sheet->setCellValueByColumnAndRow(4,$row+5, $_SESSION['cart_result'][$row]['testcode']);
            $sheet->setCellValueByColumnAndRow(5,$row+5, $_SESSION['cart_result'][$row]['testname']);
            $sheet->setCellValueByColumnAndRow(6,$row+5, $_SESSION['cart_result'][$row]['datereceived']);
            $sheet->setCellValueByColumnAndRow(7,$row+5, $_SESSION['cart_result'][$row]['timereceived']);
            $sheet->setCellValueByColumnAndRow(8,$row+5, $_SESSION['cart_result'][$row]['datereported']);
            $sheet->setCellValueByColumnAndRow(9,$row+5, $_SESSION['cart_result'][$row]['timereported']);
            $sheet->setCellValueByColumnAndRow(10,$row+5, $_SESSION['cart_result'][$row]['datepredict']);
            $sheet->setCellValueByColumnAndRow(11,$row+5, $_SESSION['cart_result'][$row]['schedule']);
            $datereported = strtotime($_SESSION['cart_result'][$row]['datereported']);
            $datepredict = strtotime($_SESSION['cart_result'][$row]['datereported']);
            $datevalid = $datepredict-$datereported;
            if($datevalid==0){
                $datevalid='Yes';
            }else{
                $datevalid="No";
            }
            $sheet->setCellValueByColumnAndRow(12,$row+5, $datevalid);
    }    
    
    $writer = new Xlsx($spreadsheet);
    $writer->save('../../../results/'.$filename1.'.xlsx');
    if($writer){
        echo 'true';
    }else{
        echo 'false';
    }
?>