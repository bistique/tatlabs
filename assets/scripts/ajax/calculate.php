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

    function _numberOfDay($name_of_day){
        switch($name_of_day){
            case 'Mon':
                $daynumber_ = 1;
                break;
            case 'Tue':
                $daynumber_ = 2;
                break;
            case 'Wed':
                $daynumber_ = 3;
                break;
            case 'Thu':
                $daynumber_ = 4;
                break;
            case 'Fri':
                $daynumber_ = 5;
                break;
            case 'Sat':
                $daynumber_ = 6;
                break;
            case 'Sun':
                $daynumber_ = 7;
                break;
        }
        return $daynumber_;
    }

    if(isset($_SESSION['cart_item'])){
        $conn2 = mysqli_connect('localhost','bistique','mimo@@##','tatlab');
        
        foreach($_SESSION['cart_item'] as $item){
            $dayarray = array();
            $_testcode=$item['testcode'];
            $result = mysqli_query($conn2,"SELECT * from _param WHERE code='$_testcode'");
            $fetchdata = mysqli_fetch_array($result);
            echo $_testcode. '   ';
            if($fetchdata){
                $code_ = $fetchdata['code'];
                $mon_ = $fetchdata['mon'];
                $daymon = '';
                $numberofday=0;
                if($mon_!=0){
                    $daymon ="Mon";
                    $numberofday=1;
                    array_push($dayarray,$daymon);
                }else{
                    $daymon=0;
                    $numberofday=1;
                }

                $tue_ = $fetchdata['tue'];
                if($tue_!=0){
                    $daytue ="Tue";
                    $numberofday=2;
                    array_push($dayarray,$daytue);
                }else{
                    $daytue=0;
                    $numberofday=2;
                }

                $wed_ = $fetchdata['wed'];
                if($wed_!=0){
                    $daywed ="Wed";
                    $numberofday=3;
                    array_push($dayarray,$daywed);
                }else{
                    $daywed=0;
                    $numberofday=3;
                }

                $thu_ = $fetchdata['thu'];
                if($thu_!=0){
                    $daythu ="Thu";
                    $numberofday=4;
                    array_push($dayarray,$daythu);
                }else{
                    $daythu=0;
                    $numberofday=4;
                }

                $fri_ = $fetchdata['fri'];
                if($fri_!=0){
                    $dayfri ="Fri";
                    $numberofday=5;
                    array_push($dayarray,$dayfri);
                }else{
                    $dayfri=0;
                    $numberofday=5;
                }

                $sat_ = $fetchdata['sat'];
                if($sat_!=0){
                    $daysat ="Sat";
                    $numberofday=6;
                    array_push($dayarray,$daysat);
                }else{
                    $daysat=0;
                    $numberofday=6;
                }

                $sun_ = $fetchdata['sun'];
                if($sun_!=0){
                    $daysun ="Sun";
                    $numberofday=7;
                    array_push($dayarray,$daysun);
                }else{
                    $daysun=0;
                    $numberofday=7;
                }

                $incubate_ = $fetchdata['incubate'];
                $workday_ = $fetchdata['workday'];
                $result_ = $fetchdata['result'];
                
                //var_dump($dayarray);
                
                // echo $code_.'   '.$daymon.' '.$mon_.'  '.$daytue.'-'.$tue_.'<br/>';
            }
            foreach($dayarray as $dayme){
                echo $dayme.' ';
            }

            $dayreceive = date('D',strtotime($item['datereceived']));
            $day_receive_number = _numberOfDay($dayreceive);
           
            $max_index = count($dayarray)-1;
            echo 'max-index : '.$max_index.'  ';;
            
            if(in_array($dayreceive,$dayarray)){
                echo '  =day found in array=  ';
                for($i=0; $i<count($dayarray);$i++){
                    if($dayarray[$i]==$dayreceive){      
                        if($i==$max_index){
                            $i=0;
                            $day_found = $dayarray[$i];
                            echo 'day receive number-->'.$day_receive_number.'  '.'  day received -->'.$dayreceive.'   day found-->'.$day_found.'<br/>';
                            break;
                        }else{
                            $i++;
                            $day_found = $dayarray[$i];
                            echo 'day receive number-->'.$day_receive_number.'  '.'  day received -->'.$dayreceive.'   day found--->'.$day_found.'<br/>';
                            break;
                        }
                    }
                }
            }else{
                echo '  not found -->'.'day receive number-->'.$day_receive_number.'  '.'  day received -->'.$dayreceive.'<br>';
            }           
            
            switch($_testcode){
                case '03272': // IGRA
                    break;
                default :
                    break;
            }
        }
    }else{
        echo 'Data Not Found';
    }

    // use PhpOffice\PhpSpreadsheet\Spreadsheet;
    // use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

    // if(isset($_FILES['file']['name'])){
    //     /* Getting file name */
    //     $filename = $_FILES['file']['tmp_name'];
    // }
    // // if($_POST){
    // //         $fotoo=addslashes(file_get_contents($_FILES['ft']['tmp_name']));
    // //         echo $fotoo;
    // // }

    // $results = [];
    // $reader = new Xlsx(); //   PhpOffice\PhpSpreadsheet\Reader\Xlsx
    // $reader->setReadDataOnly(true);
    // $spreadsheet = $reader->load($filename);//('../../../tatjuni2023.xlsx');
    // $sheet = $spreadsheet->getActiveSheet();
    // $maxrow = $spreadsheet->getActiveSheet()->getHighestRow();
    // $maxcol = $spreadsheet->getActiveSheet()->getHighestColumn();
    
    // if (!isset($_SESSION['cart_item'])) {
    //     $_SESSION['cart_item'] = array();
    // }
	
    // $ColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($maxcol);
    // for ($row = 5; $row <= $maxrow-3; ++ $row) {
    //     $val = array();
       
    //     for ($col = 1; $col < $ColumnIndex; ++ $col) {
    //         $cell = $sheet->getCellByColumnAndRow($col, $row);
    //         if($cell->getValue()==''){
    //             $value=' ';
    //         }else{
    //             $value=$cell->getValue();
    //         }
    //         switch($col){
    //             case 1:
    //                 $no = $value;
    //                 break;
    //             case 2:
    //                 $accession_no = $value;
    //                 break;
    //             case 3:
    //                 $patient_name = $value;
    //                 break;
    //             case 4:
    //                 $testcode = $value;
    //                 break;
    //             case 5:
    //                 $testname = $value;
    //                 break;
    //             case 6:
    //                 $datereceived = strtotime($value);
    //                 $datereceived = date('d M Y', $datereceived);
    //                 break;
    //             case 7:
    //                 $timereceived = strtotime($value);
    //                 $timereceived = date('H:i',$timereceived);
    //                 break;
    //             case 8:
    //                 $datereported = strtotime($value);
    //                 $datereported = date('d M Y', $datereported);
    //                 break;
    //             case 9:
    //                 $timereported = strtotime($value);
    //                 $timereported = date('H:i',$timereported);
    //                 break;
    //         }
            
    //         // $val[] = $cell->getValue();
    //         //array_push($_SESSION['cart_item'],$itemArray);
    //     }
    //     $itemArray = array('no'=>$no,'accession_no'=>$accession_no,'patient_name'=>$patient_name,'testcode'=>$testcode,'testname'=>$testname,'datereceived'=>$datereceived,'timereceived'=>$timereceived,'datereported'=>$datereported,'timereported'=>$timereported);
    //     array_push($_SESSION['cart_item'],$itemArray);
    // }
   
    // $import= '<table class="table table-hover"><small>
    //         <thead>
    //             <th class="text-center "><medium>NO</medium></th>
    //             <th class="text-center"><medium>ACESSION NO</medium></th>
    //             <th class="text-center"><medium>PATIENT NAME</medium></th>
    //             <th class="text-center"><medium>TEST CODE</medium></td>
    //             <th class="text-center"><medium>TEST NAME</medium></th>
    //             <th class="text-center"><medium>DATE RECEIVED</medium></th>
    //             <th class="text-center"><medium>TIME RECEIVED</medium></th>
    //             <th class="text-center"><medium>DATE REPORTED</medium></th>
    //             <th class="text-center"><medium>TIME REPORTED</medium></th>
    //         </thead>';
        
    // $conn2 = mysqli_connect('localhost','bistique','mimo@@##','tatlab');
    // foreach($_SESSION['cart_item'] as $item){
    //     $import.='<tr>
    //             <td class="text-center"><medium>'.$item['no'].'</medium></td>
    //             <td class="text-center"><medium>'.$item['accession_no'].'</medium></td>
    //             <td class="text-right"><medium>'.$item['patient_name'].'</medium></td>
    //             <td class="text-center"><medium>'.$item['testcode'].'</medium></td>
    //             <td class="text-left"><medium>'.$item['testname'].'</medium></td>
    //             <td class="text-center"><medium>'.$item['datereceived'].'</medium></td>
    //             <td class="text-center"><medium>'.$item['timereceived'].'</medium></td>
    //             <td class="text-center"><medium>'.$item['datereported'].'</medium></td>
    //             <td class="text-center"><medium>'.$item['timereported'].'</medium></td>
    //         </tr>';
    //         // $code_ = $item['code'];
    //         // $param_ = $item['param'];
    //         // $mon_ = $item['mon'];
    //         // $tue_ = $item['tue'];
    //         // $wed_ = $item['wed'];
    //         // $thu_ = $item['thu'];
    //         // $fri_ = $item['fri'];
    //         // $sat_ = $item['sat'];
    //         // $sun_ = $item['sun'];
    //         // $workday_ = $item['workday'];
    //         // $incubate_ = $item['incubate'];
    //         // $result_ = $item['result'];

    //         // $result = mysqli_query($conn2,"insert into _param (code, param,mon,tue,wed,thu,fri,sat,sun,workday,incubate,result) values ('$code_', '$param_','$mon_', '$tue_','$wed_','$thu_','$fri_','$sat_','$sun_','$workday_','$incubate_','$result_');");

    // }
    // $import.= '</small></table>';
    // echo $import;
?>