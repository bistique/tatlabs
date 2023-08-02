<?php
    /*******************************************
     * Created by   : Ary Herijanto
     * Date         : 10th June 2023
     * Made For     : ABC Laboratorium Jakarta
     * Libs         : PHPOFFICE/PHPSPREADSHEET
     *                PHPMAILER  
     *                COMPOSER
     *
     *******************************************/
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
    require_once 'holiday.php';
    $mimopredict='testmf';
    
    if(isset($_SESSION['cart_result'])){
        unset($_SESSION['cart_result']);
    }
    function updateDate($drv,$dx1){
        //echo $drv;
        $mod_date = date('d M Y', strtotime("+".$dx1." day", strtotime($drv)));
        return $mod_date;
    }

    // dayarray1 -> schedule day per test code got from table params
        // dayreceive1 -> name of the day sample received
        // daynumber -> a number in week of the day receive
        // max_index -> maximum count of index of dayarray1
        // listday -> 'Mon','Tue','Wed','Thu','Fri','Sat','Sun'
        // daymreceive -> date of sample receive eg. '06/02/2023'

    function find_day($no__,$code__,$dayarray1,$dayreceive1,$daynumber,$max_index,$listday1,$daymereceive,$mtimereceive){
        
        $daystepcounter = 0;

        if($max_index==0){
            $i=0;
            $day_found = $dayarray1[$i]['day'];
        }
        
        for($i=0;$i<count($dayarray1);$i++){
            if($dayarray1[$i]['day']==$dayreceive1){
                
                if(substr($dayarray1[$i]['time'],0,1)=='<'){
                    $time_rec_extract =  substr($dayarray1[$i]['time'],1,strlen($dayarray1[$i]['time'])-1).'  ';
                    if ($mtimereceive < $time_rec_extract){
                        //do today
                        $day_found = $dayarray1[$i]['day'];
                        $dpredict=date('d M Y',strtotime($daymereceive));
                        break; 
                    }
                    if ($mtimereceive > $time_rec_extract){
                            if($i==$max_index){
                                $i=0;
                                $day_found = $dayarray1[$i]['day'];
                                $diffdaynumber = findStepDay($dayreceive1,$dayarray1,$day_found,$listday1);
                                $dpredict = updateDate($daymereceive,$diffdaynumber);
                                break;
                            }else{
                                $i++;
                                $day_found = $dayarray1[$i]['day'];
                                $diffdaynumber = findStepDay($dayreceive1,$dayarray1,$day_found,$listday1);
                                $dpredict = updateDate($daymereceive,$diffdaynumber);
                            }
                    }
                }else{
                    if($mtimereceive > $dayarray1[$i]['time']){
                        if($i==$max_index){
                                $i=0;
                                $day_found = $dayarray1[$i]['day'];
                                $diffdaynumber = findStepDay($dayreceive1,$dayarray1,$day_found,$listday1);
                                $dpredict = updateDate($daymereceive,$diffdaynumber);
                                break;
                        }else{
                                $i++;
                                $day_found = $dayarray1[$i]['day'];
                                $diffdaynumber = findStepDay($dayreceive1,$dayarray1,$day_found,$listday1);
                                $dpredict = updateDate($daymereceive,$diffdaynumber);
                                break;
                        }      //do next day
                    }
                    if($mtimereceive < $dayarray1[$i]['time']){
                        //do today    
                        $day_found = $dayarray1[$i]['day'];
                        $dpredict=date('d M Y',strtotime($daymereceive)); 
                        break;
                    }
                }
            }
        }
        //
        return $dpredict;
       
    }

    function findStepDay($dr,$dar,$df,$ldar){
        // dr -> day receive
        // dar -> array of day receive eg.{'Mon','Fri'}
        // df -> day found from dar
        // ldar -> list days of week

        $ndr = _numberOfDay($dr);
        $ndf = _numberOfDay($df);
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
            $dreceive = date('m/d/Y',strtotime($item['datereceived']));
            $dayreceive = date('D',strtotime($item['datereceived']));
            $day_receive_number = _numberOfDay($dayreceive);
            $listday = array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
            $max_index = count($dayarray)-1;
            
            if(in_array($dayreceive,array_column($dayarray,'day'))){//in_array($dayreceive,$dayarray)){
                $sp = find_day($_no,$code_,$dayarray,$dayreceive,$day_receive_number,$max_index,$listday,$dreceive,$_timereceived);
            }else{
                $lowcounter = $day_receive_number;
                $maxcounter = 7;
                $stepday = 0;
                for($counter = $lowcounter-1;$counter<=$maxcounter-1;$counter++){
                    $stepday++;
                    if($counter == $maxcounter-1){
                        // $stepday++;
                        for($newcounter=0;$newcounter<$lowcounter;$newcounter++){
                            $stepday++;
                            if(in_array($listday[$newcounter],array_column($dayarray,'day'))){
                                $day_found = $listday[$newcounter];
                                $dx = findStepDay($dayreceive,$dayarray,$day_found,$listday);
                                $sp = updateDate($dreceive,$dx);
                                break;
                            }
                        }
                    }else{
                        if(in_array($listday[$counter],array_column($dayarray,'day'))){
                            $day_found = $listday[$counter];
                            // echo $day_found.'----'.$_testcode.'---'.$dayreceive.'<br/>';
                            // var_dump($dayarray);
                            $dx = findStepDay($dayreceive,$dayarray,$day_found,$listday);
                            $sp = updateDate($dreceive,$dx);   
                            break;
                        }
                    }
                }
            }
            if($workday_ > 0){
                $sp = date('d M Y', strtotime("+".$workday_." day", strtotime($sp)));
            }
            $isHoliday = find_holiday($sp);
            switch ($isHoliday) {
                case 'holiday':
                    # code...
                    $dreceivesp = date('m/d/Y',strtotime($sp));
                    $dayreceivesp = date('D',strtotime($sp));
                    $day_receive_number = _numberOfDay($dayreceivesp);
                    $sp1 = find_day($_no,$code_,$dayarray,$dayreceivesp,$day_receive_number,$max_index,$listday,$dreceivesp,$_timereceived);
                    $itemArrayResult = array('no'=>$_no,'accession_no'=>$_accessionno,'patient_name'=>$_patientname,'testcode'=>$_testcode,'testname'=>$_testname,'datereceived'=>$_datereceived,'timereceived'=>$_timereceived,'datereported'=>$_datereported,'timereported'=>$_timereported,'datepredict'=>$sp1,'schedule'=>$dayme_mimo,'result'=>$result_,'libur'=>$isHoliday);
                    break;
                case 'workday';
                    $itemArrayResult = array('no'=>$_no,'accession_no'=>$_accessionno,'patient_name'=>$_patientname,'testcode'=>$_testcode,'testname'=>$_testname,'datereceived'=>$_datereceived,'timereceived'=>$_timereceived,'datereported'=>$_datereported,'timereported'=>$_timereported,'datepredict'=>$sp,'schedule'=>$dayme_mimo,'result'=>$result_,'libur'=>$isHoliday);
                    break;
            }
            array_push($_SESSION['cart_result'],$itemArrayResult);
         }
    }else{
       
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
                <th class="text-center"><small>RESULT</small></th>
                <th class="text-center"><small>HOLIDAY</small></th>

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
                <td class="text-center bg-secondary text-white"><small>'.$result['result'].'</small></td>
                <td class="text-center bg-secondary text-white"><small>'.$result['libur'].'</small></td>
            </tr>';

    }
    echo $tatcalculate;
?>