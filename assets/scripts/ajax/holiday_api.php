<?php
    session_start();
    error_reporting(E_ALL);
    ini_set("display_errors", "On");
    date_default_timezone_set("Asia/Bangkok");
    $mydaymonth = $_POST['mymonth'];
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api-harilibur.vercel.app/api?month='.$mydaymonth,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    $holiday = json_decode($response);
    $no=0;
    if($holiday!=' '){
        $hl_data = '<table class="table table-hover">
                    <thead>
                        <th class="text-center">NO</th>
                        <th class="text-center">DATE</th>
                        <th class="text-center">NAME</th>
                        <th class="text-center">STATUS</th>
                    </thead>';
        foreach($holiday as $hl){
            $no++;
            if($hl->is_national_holiday){
                $status ='Hari Libur Nasional';
            }else{
                $status = 'Bukan Hari Libur Nasional'; 
            }
            $hl_data.='<tr>
                            <td class="text-center">'.$no.'</td>
                            <td>'.date('D, d-M-Y',strtotime($hl->holiday_date)).'</td>
                            <td>'.$hl->holiday_name.'</td>
                            <td class="text-center">'.$status.'</td>
                         </tr>';
        }
        $hl_data.='</table>';
        echo $hl_data;
    }else{
        echo 'Data Not Available';
    }
    
?>
