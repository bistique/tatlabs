<?php
    $filename1 = $_POST['filename'];
    if(file_exists('../../../results/'.$filename1.'.xlsx')){
        echo 'exist';
    }else{
        echo 'failed';
    }
?>