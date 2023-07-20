 <?php
 /**
 * Created by   : Ary Herijanto
 * Date         : 10th June 2023
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
    use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

    if(isset($_FILES['file']['name'])){
        /* Getting file name */
        $filename = $_FILES['file']['tmp_name'];
    }
    // if($_POST){
    //         $fotoo=addslashes(file_get_contents($_FILES['ft']['tmp_name']));
    //         echo $fotoo;
    // }

    $results = [];
    $reader = new Xlsx(); //   PhpOffice\PhpSpreadsheet\Reader\Xlsx
    $reader->setReadDataOnly(true);
    $spreadsheet = $reader->load($filename);//('../../../tatjuni2023.xlsx');
    $sheet = $spreadsheet->getActiveSheet();
    $maxrow = $spreadsheet->getActiveSheet()->getHighestRow();
    $maxcol = $spreadsheet->getActiveSheet()->getHighestColumn();
    echo $maxrow;

    if(isset($_SESSION['cart_item'])){
        unset($_SESSION['cart_item']);
    }
    
    if (!isset($_SESSION['cart_item'])) {
        $_SESSION['cart_item'] = array();
    }
	
    $ColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($maxcol);
    for ($row = 5; $row <= $maxrow-7; ++ $row) {
        $val = array();
       
        for ($col = 1; $col < $ColumnIndex; ++ $col) {
            $cell = $sheet->getCellByColumnAndRow($col, $row);
            if($cell->getValue()==''){
                $value=' ';
            }else{
                $value=$cell->getValue();
            }
            switch($col){
                case 1:
                    $no = $value;
                    break;
                case 2:
                    $accession_no = $value;
                    break;
                case 3:
                    $patient_name = $value;
                    break;
                case 4:
                    $testcode = $value;
                    break;
                case 5:
                    $testname = $value;
                    break;
                case 6:
                    $datereceived = strtotime($value);
                    $datereceived = date('d M Y', $datereceived);
                    break;
                case 7:
                    $timereceived = strtotime($value);
                    $timereceived = date('H:i',$timereceived);
                    break;
                case 8:
                    $datereported = strtotime($value);
                    $datereported = date('d M Y', $datereported);
                    break;
                case 9:
                    $timereported = strtotime($value);
                    $timereported = date('H:i',$timereported);
                    break;
            }
            
            // $val[] = $cell->getValue();
            //array_push($_SESSION['cart_item'],$itemArray);
        }
        $itemArray = array('no'=>$no,'accession_no'=>$accession_no,'patient_name'=>$patient_name,'testcode'=>$testcode,'testname'=>$testname,'datereceived'=>$datereceived,'timereceived'=>$timereceived,'datereported'=>$datereported,'timereported'=>$timereported);
        array_push($_SESSION['cart_item'],$itemArray);
    }
   
    $import= '<table class="table table-hover"><small>
            <thead>
                <th class="text-center "><medium>NO</medium></th>
                <th class="text-center"><medium>ACESSION NO</medium></th>
                <th class="text-center"><medium>PATIENT NAME</medium></th>
                <th class="text-center"><medium>TEST CODE</medium></td>
                <th class="text-center"><medium>TEST NAME</medium></th>
                <th class="text-center"><medium>DATE RECEIVED</medium></th>
                <th class="text-center"><medium>TIME RECEIVED</medium></th>
                <th class="text-center"><medium>DATE REPORTED</medium></th>
                <th class="text-center"><medium>TIME REPORTED</medium></th>
            </thead>';
        
    $conn2 = mysqli_connect('localhost','bistique','mimo@@##','tatlab');
    foreach($_SESSION['cart_item'] as $item){
        $import.='<tr>
                <td class="text-center"><medium>'.$item['no'].'</medium></td>
                <td class="text-center"><medium>'.$item['accession_no'].'</medium></td>
                <td class="text-right"><medium>'.$item['patient_name'].'</medium></td>
                <td class="text-center"><medium>'.$item['testcode'].'</medium></td>
                <td class="text-left"><medium>'.$item['testname'].'</medium></td>
                <td class="text-center"><medium>'.$item['datereceived'].'</medium></td>
                <td class="text-center"><medium>'.$item['timereceived'].'</medium></td>
                <td class="text-center"><medium>'.$item['datereported'].'</medium></td>
                <td class="text-center"><medium>'.$item['timereported'].'</medium></td>
            </tr>';
            // $code_ = $item['code'];
            // $param_ = $item['param'];
            // $mon_ = $item['mon'];
            // $tue_ = $item['tue'];
            // $wed_ = $item['wed'];
            // $thu_ = $item['thu'];
            // $fri_ = $item['fri'];
            // $sat_ = $item['sat'];
            // $sun_ = $item['sun'];
            // $workday_ = $item['workday'];
            // $incubate_ = $item['incubate'];
            // $result_ = $item['result'];

            // $result = mysqli_query($conn2,"insert into _param (code, param,mon,tue,wed,thu,fri,sat,sun,workday,incubate,result) values ('$code_', '$param_','$mon_', '$tue_','$wed_','$thu_','$fri_','$sat_','$sun_','$workday_','$incubate_','$result_');");

    }
    $import.= '</small></table>';
    echo $import;
?>