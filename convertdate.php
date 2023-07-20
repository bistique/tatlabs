<?php
$time_input = strtotime("02 Jun 2023"); 
$date_input = getDate($time_input); 
//print_r($date_input);
echo date('m/d/Y',$time_input);                
?>