<?php
    /**
     * Created by   : Ary Herijanto
     * Date         : 10th June 2023
     * Made For     : ABC Laboratorium Jakarta
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
    $mimopredict='testmf';

    if(isset($_SESSION['cart_result'])){
        unset($_SESSION['cart_result']);
    }
    function updateDate($drv,$dx1){
        //echo $drv;
        $mod_date = date('d M Y', strtotime("+".$dx1." day", strtotime($drv)));
        return $mod_date;
    }

    function find_day($dayarray1,$dayreceive1,$daynumber,$max_index,$listday1,$daymereceive,$mtimereceive){
        // dayarray1 -> schedule day per test code got from table params
        // dayreceive1 -> name of the day sample received
        // daynumber -> a number in week of the day receive
        // max_index -> maximum count of index of dayarray1
        // listday -> 'Mon','Tue','Wed','Thu','Fri','Sat','Sun'
        // daymreceive -> date of sample receive eg. '06/02/2023'

        $daystepcounter = 0;

       // echo $dayreceive1.'---'.$daynumber.'---'.$max_index;
        if($max_index==0){
            $i=0;
            $day_found = $dayarray1[$i]['day'];
        }

        for($i=0; $i<count($dayarray1);$i++){
            if($dayarray1[$i]['day']==$dayreceive1){
                if(substr($dayarray1[$i]['time'],0,1)=='<'){
                    $time_rec_extract =  substr($dayarray1[$i]['time'],1,strlen($dayarray1[$i]['time'])-1).'  ';
                    if ($mtimereceive < $time_rec_extract){
                        echo 'time receive is : '.$mtimereceive.' less than '.$time_rec_extract.'  ';
                        //do today
                        $day_found = $dayarray1[$i]['day'];
                        $dpredict=date('d M Y',strtotime($daymereceive)); 
                    }

                }else{
                    if($mtimereceive > $dayarray1[$i]['time']){
                        echo 'lebih besar dan in array......terima : '.$mtimereceive.' jadwal : '.$dayarray1[$i]['day'].' - '.$dayarray1[$i]['time'].'  ';
                        // echo 'time receive is : '.$mtimereceive.' greater than '.$dayarray1[$i]['time'].'  ';
                        if($i==$max_index){
                                $i=0;
                                $day_found = $dayarray1[$i]['day'];
                                $diffdaynumber = findStepDay($dayreceive1,$dayarray1,$day_found,$listday1);
                                $dpredict = updateDate($daymereceive,$diffdaynumber);
                                break;
                        }else{
                                echo 'jika index tidak = max hari terima : '.$dayreceive1.' -- '.$daymereceive;
                                $i++;
                                $day_found = $dayarray1[$i]['day'];
                                $diffdaynumber = findStepDay($dayreceive1,$dayarray1,$day_found,$listday1);
                                $dpredict = updateDate($daymereceive,$diffdaynumber);
                                //echo 'day receive number-->'.$daynumber.'  '.'  day received -->'.$dayreceive1.'   day found--->'.$day_found.'<br/>';
                                echo '  hari ketemu : '.$day_found.' selisih hari : '.$diffdaynumber.'    ';
                                break;
                        }
                        
                        
                        //do next day
                    }
                    if($mtimereceive < $dayarray1[$i]['time']){
                        echo 'time receive is : '.$mtimereceive.' less than '.$dayarray1[$i]['time'].'  ';
                        //do today    
                        $day_found = $dayarray1[$i]['day'];
                        $dpredict=date('d M Y',strtotime($daymereceive)); 
                        break;
                       
                    }
                }
                     
                // if($i==$max_index){
                //     $i=0;
                //     $day_found = $dayarray1[$i]['day'];
                //     break;
                // }else{
                //     $i++;
                //     $day_found = $dayarray1[$i]['day'];
                //     //echo 'day receive number-->'.$daynumber.'  '.'  day received -->'.$dayreceive1.'   day found--->'.$day_found.'<br/>';
                //     break;
                // }
            }
        }
        //
       
      
        return $day_found.'   '.$dpredict;
        //echo 'day receive number-->'.$daynumber.'  '.'  day received -->'.$dayreceive1.'   day found-->'.$day_found.'  dSTEP : '.$diffdaynumber.' date Valid : '.$datepredict1.'<br/>';
    }

    function findStepDay($dr,$dar,$df,$ldar){
        // dr -> day receive
        // dar -> array of day receive eg.{'Mon','Fri'}
        // df -> day found from dar
        // ldar -> list days of week

        $ndr = _numberOfDay($dr);
        $ndf = _numberOfDay($df);
        echo '<br/>number of day receive : '.$dr.'--'.$ndr;
        $lod = 7;
        $ddate = 0;
        for($counter = $ndr-1;$counter<=$lod-1;$counter++){
            $ddate++;
            if($ndr>$ndf){
                if($counter==$lod-1){
                    $lowcounter1 = 1;
                    $maxcounter = $ndr;
                    for($newcounter1=0;$newcounter1<$lod;$newcounter1++){
                        $ddate++;
                        if(in_array($ldar[$newcounter1],array_column($dar,'day'))){
                            break;
                        }    
                    }
                }
            }else{
                $ddate=1;
                for($newcounter1=$ndr+1;$newcounter1<=$ndf;$newcounter1++){
                    $ddate++;
                    if(in_array($ldar[$counter],array_column($dar,'day'))){
                        break;
                    }  
                }
            }    
            
        }
        return $ddate-1;
    }

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
        //$itemArray = array('no'=>$no,'accession_no'=>$accession_no,'patient_name'=>$patient_name,'testcode'=>$testcode,'testname'=>$testname,'datereceived'=>$datereceived,'timereceived'=>$timereceived,'datereported'=>$datereported,'timereported'=>$timereported);
    //     array_push($_SESSION['cart_item'],$itemArray);
        if (!isset($_SESSION['cart_result'])) {
            $_SESSION['cart_result'] = array();
        }
        
        foreach($_SESSION['cart_item'] as $item){
            $dayarray = array();

            $_no = $item['no'];
            $_accessionno = $item['accession_no'];
            $_patientname = $item['patient_name'];
            $_testcode = $item['testcode'];
            $_testname = $item['testname'];
            $_datereceived = $item['datereceived'];
            $_timereceived = $item['timereceived'];
            $_datereported = $item['datereported'];
            $_timereported = $item['timereported'];

            $result = mysqli_query($conn2,"SELECT * from _param WHERE code='$_testcode'");
            $fetchdata = mysqli_fetch_array($result);
            
            //Get data time from database  per code test
            if($fetchdata){
                $code_ = $fetchdata['code'];

                $mon_ = $fetchdata['mon'];
                $daymon = array();
                $numberofday=0;
                if($mon_!=0){
                    $daymon =array('day'=>'Mon','time'=>$fetchdata['mon']);
                    $numberofday=1;
                    array_push($dayarray,$daymon);
                }else{
                    // $daymon=array();
                    $numberofday=1;
                }

                $tue_ = $fetchdata['tue'];
                if($tue_!=0){
                    $daytue =array('day'=>'Tue','time'=>$fetchdata['tue']);
                    $numberofday=2;
                    array_push($dayarray,$daytue);
                }else{
                    // $daytue=array();
                    $numberofday=2;
                }

                $wed_ = $fetchdata['wed'];
                if($wed_!=0){
                    $daywed =array('day'=>'Wed','time'=>$fetchdata['wed']);
                    $numberofday=3;
                    array_push($dayarray,$daywed);
                }else{
                    // $daywed=0;
                    $numberofday=3;
                }

                $thu_ = $fetchdata['thu'];
                if($thu_!=0){
                    $daythu =array('day'=>'Thu','time'=>$fetchdata['thu']);
                    $numberofday=4;
                    array_push($dayarray,$daythu);
                }else{
                    // $daythu=0;
                    $numberofday=4;
                }

                $fri_ = $fetchdata['fri'];
                if($fri_!=0){
                    $dayfri =array('day'=>'Fri','time'=>$fetchdata['fri']);
                    $numberofday=5;
                    array_push($dayarray,$dayfri);
                }else{
                    // $dayfri=0;
                    $numberofday=5;
                }

                $sat_ = $fetchdata['sat'];
                if($sat_!=0){
                    $daysat =array('day'=>'Sat','time'=>$fetchdata['sat']);
                    $numberofday=6;
                    array_push($dayarray,$daysat);
                }else{
                    // $daysat=0;
                    $numberofday=6;
                }

                $sun_ = $fetchdata['sun'];
                if($sun_!=0){
                    $daysun =array('day'=>'Sun','time'=>$fetchdata['sun']);
                    $numberofday=7;
                    array_push($dayarray,$daysun);
                }else{
                    // $daysun=0;
                    $numberofday=7;
                }
                $incubate_ = $fetchdata['incubate'];
                $workday_ = $fetchdata['workday'];
                $result_ = $fetchdata['result'];
            }
       
            $dayme_mimo='';
       
            foreach($dayarray as $dayme){
                $dayme_mimo.=$dayme['day'];
            }
           // var_dump($dayarray);
            $dreceive = date('m/d/Y',strtotime($item['datereceived']));
            $dayreceive = date('D',strtotime($item['datereceived']));
            $day_receive_number = _numberOfDay($dayreceive);
            $listday = array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
            $max_index = count($dayarray)-1;
            
            if(in_array($dayreceive,array_column($dayarray,'day'))){//in_array($dayreceive,$dayarray)){
             
             $sp = find_day($dayarray,$dayreceive,$day_receive_number,$max_index,$listday,$dreceive,$_timereceived);
             echo $code_.'---> '.$dayreceive.'---> '.$sp.'<br/>';
            }else{
                // $lowcounter = $day_receive_number;
                // $maxcounter = 7;
                // //echo 'low counter : '.$lowcounter.'  max counter'.$maxcounter.'<br/>';
                // $ddate1 = 0;
                // if($max_index==0){
                //     $maxcounter = 0;
                //     $day_found = $dayarray[0];
                // }
                
                // for($counter = $lowcounter-1;$counter<=$maxcounter-1;$counter++){
                //     $ddate1++;
                //     if($counter==$maxcounter-1){
                //         $lowcounter = 1;
                //         $maxcounter = $day_receive_number;
                //         for($newcounter=0;$newcounter<$maxcounter;$newcounter++){
                //             $ddate1++;
                //             if(in_array($listday[$newcounter],$dayarray)){
                //                 $day_found = $listday[$newcounter];
                //                 break;
                //             }    
                //         }
                //     }
                // }
                // $dx = findStepDay($dayreceive,$dayarray,$day_found,$listday);
                // $dpredict = updateDate($dreceive,$dx);
                // $mimopredict = $dpredict;
                //=======================================================================
                //echo '  not found -->'.'day receive number-->'.$day_receive_number.'  '.'  day received -->'.$dayreceive.'   day found-->'.$day_found.'dStep : '.$dx.'  date Valid : '.$dpredict.'<br/>';
            }
            
            
        //    $itemArrayResult = array('no'=>$_no,'accession_no'=>$_accessionno,'patient_name'=>$_patientname,'testcode'=>$_testcode,'testname'=>$_testname,'datereceived'=>$_datereceived,'timereceived'=>$_timereceived,'datereported'=>$_datereported,'timereported'=>$_timereported,'datepredict'=>$dpredict,'schedule'=>$dayme_mimo);

        //     array_push($_SESSION['cart_result'],$itemArrayResult);
        //     //echo '<br/>';
         }
    }else{
       // echo 'Data Not Found';
    }
    $tatcalculate =  '<table class="table table-hover table-strip"><small>
            <thead>
                <th class="text-center "><small>NO</small></th>
                <th class="text-center"><small>ACESSION NO</small></th>
                <th class="text-center"><small>PATIENT NAME</small></th>
                <th class="text-center"><small>TEST CODE</small></td>
                <th class="text-center"><small>TEST NAME</small></th>
                <th class="text-center"><small>DATE RECEIVED</small></th>
                <th class="text-center"><small>TIME RECEIVED</small></th>
                <th class="text-center"><small>DATE REPORTED</small></th>
                <th class="text-center"><small>TIME REPORTED</small></th>
                <th class="text-center"><small>DATE TAT</small></th>
                <th class="text-center"><small>SCHEDULE</small></th>

            </thead>';
    foreach($_SESSION['cart_result'] as $result){
        $tatcalculate.='<tr>
                <td class="text-center"><small>'.$result['no'].'</small></td>
                <td class="text-center"><small>'.$result['accession_no'].'</small></td>
                <td class="text-right"><small>'.$result['patient_name'].'</small></td>
                <td class="text-center"><small>'.$result['testcode'].'</small></td>
                <td class="text-left"><small>'.$result['testname'].'</small></td>
                <td class="text-center"><small>'.$result['datereceived'].'</small></td>
                <td class="text-center"><small>'.$result['timereceived'].'</small></td>
                <td class="text-center"><small>'.$result['datereported'].'</small></td>
                <td class="text-center"><small>'.$result['timereported'].'</small></td>
                <td class="text-center bg-primary text-white"><small>'.$result['datepredict'].'</small></td>
                <td class="text-center bg-info text-white"><small>'.$result['schedule'].'</small></td>
            </tr>';

    }
    echo $tatcalculate;

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