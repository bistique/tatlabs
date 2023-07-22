<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Asia/Jakarta');
$dayarray = array(array('day'=>'Mon','time'=>'08:00'),
                  array('day'=>'Wed','time'=>'08:00'),
                array('day'=>'Fri','time'=>'08:00'));

$dayreceive = 'Mon';
if(in_array($dayreceive,array_column($dayarray,'day'))){
  echo 'here';
  echo $dayarray[1]['day'].' -- '.$dayarray[0]['time'];
}else{
  echo 'notfound';
}
?>