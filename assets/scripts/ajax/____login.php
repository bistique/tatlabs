<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);
    date_default_timezone_set('Asia/Jakarta');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Content-Type');

    $user__ = $_POST['username'];
    $pswd__ = $_POST['pwd'];

    $conn2 = mysqli_connect('localhost','bistique','mimo@@##','tatlab');
    $queryuser = "SELECT * FROM _authuser WHERE username_='$user__' AND pwd_='$pswd__'";
    $runquery = mysqli_query($conn2,$queryuser);
    $rowresult = mysqli_num_rows($runquery);
    if($rowresult>0){
        echo 'ok';
    }else{
        echo 'failed';
    }

    


?>