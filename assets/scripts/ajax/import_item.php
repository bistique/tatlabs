<?php
    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);
    date_default_timezone_set('Asia/Jakarta');
    require_once '../../../vendor/autoload.php';
    require_once '../../../lib/config.php';
    require_once '../../../lib/header.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Reader\Xlsx;


    $results = [];
    $reader = new Xlsx(); //   PhpOffice\PhpSpreadsheet\Reader\Xlsx
    $reader->setReadDataOnly(true);
    $spreadsheet = $reader->load('../../../tat.xlsx');
    $sheet = $spreadsheet->getActiveSheet();
    $maxrow = $spreadsheet->getActiveSheet()->getHighestRow();
    $maxcol = $spreadsheet->getActiveSheet()->getHighestColumn();
    
    if (!isset($_SESSION['cart_item'])) {
        $_SESSION['cart_item'] = array();
    }

    echo MYROOT;
	
    $ColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($maxcol);
    for ($row = 4; $row <= $maxrow; ++ $row) {
        $val = array();
        $col = 1;
        for ($col = 2; $col < $ColumnIndex; ++ $col) {
            $cell = $sheet->getCellByColumnAndRow($col, $row);
            if($cell->getValue()==''){
                $value=0;
            }else{
                $value=$cell->getValue();
            }
            switch($col){
                case 2:
                    $code = $value;
                    break;
                case 3:
                    $param = $value;
                    break;
                case 4:
                    $mon = $value;
                    break;
                case 5:
                    $tue = $value;
                    break;
                case 6:
                    $wed = $value;
                    break;
                case 7:
                    $thu = $value;
                    break;
                case 8:
                    $fri = $value;
                    break;
                case 9:
                    $sat = $value;
                    break;
                case 10:
                    $sun = $value;
                    break;
                case 11:
                    $workday = $value;
                    break;
                case 12:
                    $incubate = $value;
                    break;
                case 13:
                    $result = $value;
                    break;
            }
            
            // $val[] = $cell->getValue();
            //array_push($_SESSION['cart_item'],$itemArray);
        }
        $itemArray = array('code'=>$code,'param'=>$param, 'mon'=>$mon,'tue'=>$tue,'wed'=>$wed,'thu'=>$thu,'fri'=>$fri,'sat'=>$sat,'sun'=>$sun,'workday'=>$workday,'incubate'=>$incubate,'result'=>$result);
        array_push($_SESSION['cart_item'],$itemArray);
    }
   
    echo '<table class="table table-hover">
            <thead>
                <th class="text-center">CODE</th>
                <th class="text-center">PARAM</th>
                <th class="text-center">MON</td>
                <th class="text-center">TUE</th>
                <th class="text-center">WED</th>
                <th class="text-center">THU</th>
                <th class="text-center">FRI</th>
                <th class="text-center">SAT</th>
                <th class="text-center">SUN</th>
                <th class="text-center">WORKDAY</th>
                <th class="text-center">INCUBATE</th>
                <th class="text-center">RESULT</th>
            </thead>';
        
    $conn2 = mysqli_connect('localhost','bistique','mimo@@##','tatlab');
    foreach($_SESSION['cart_item'] as $item){
        echo '<tr>
                <td align="center">'.$item['code'].'</td>
                <td>'.$item['param'].'</td>
                <td align="center">'.$item['mon'].'</td>
                <td align="center">'.$item['tue'].'</td>
                <td align="center">'.$item['wed'].'</td>
                <td align="center">'.$item['thu'].'</td>
                <td align="center">'.$item['fri'].'</td>
                <td align="center">'.$item['sat'].'</td>
                <td align="center">'.$item['sun'].'</td>
                <td align="center">'.$item['workday'].'</td>
                <td align="center">'.$item['incubate'].'</td>
                <td align="center">'.$item['result'].'</td>
            </tr>';
            $code_ = $item['code'];
            $param_ = $item['param'];
            $mon_ = $item['mon'];
            $tue_ = $item['tue'];
            $wed_ = $item['wed'];
            $thu_ = $item['thu'];
            $fri_ = $item['fri'];
            $sat_ = $item['sat'];
            $sun_ = $item['sun'];
            $workday_ = $item['workday'];
            $incubate_ = $item['incubate'];
            $result_ = $item['result'];

           // $result = mysqli_query($conn2,"insert into _param (code, param,mon,tue,wed,thu,fri,sat,sun,workday,incubate,result) values ('$code_', '$param_','$mon_', '$tue_','$wed_','$thu_','$fri_','$sat_','$sun_','$workday_','$incubate_','$result_');");

    }
    echo '</table>';
?>