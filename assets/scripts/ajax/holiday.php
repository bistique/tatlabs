<?php
//
  function find_holiday($mydaymonth){
    
    $month = date('m',strtotime($mydaymonth));
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api-harilibur.vercel.app/api?month='.$month,
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
    
    $date_find = date('Y-m-d',strtotime($mydaymonth));
   
    if (in_array($date_find,array_column($holiday,'holiday_date'))){
      return 'holiday';
    }else{
      return 'workday';
    }
  }

?>
