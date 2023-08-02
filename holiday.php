<?php
    session_start();
    error_reporting(E_ALL);
    ini_set("display_errors", "On");
    date_default_timezone_set("Asia/Bangkok");
    require_once './lib/config.php';
    require_once './lib/header.php';
?>

<!DOCTYPE html>
<html>
    <body>
        <div class="card">
            <div class="mt-5 text-center">
                <img class="shadow p-1 mb-5 bg-white" src="take-a-vacation.gif" id="logoanim" width="200px" style="border-radius:50%;">
            </div>
            <div class="mt-5 text-center">
                <label><h2>Holiday</h2></label>
            </div>
            <div class="mt-1">
                <select class="form-control form-control-lg mx-auto" id="_month" style="width:300px;">
                    <option selected>Open This Select Month</option>
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
            <div class="mt-3 text-center mb-3">
                <button class="btn btn-success text-right" id="findholiday">Find Holiday</button>
            </div>
        </div>
       

        <div class="mt-4" id="resultholiday"></div>
                
    </body>
    <div id="toasttat" class="toast" style="position: absolute; top: 0; right: 0;" data-autohide="false">
                <img src="infologo.png" class="rounded mr-2" alt="..." width="32px">
                <strong class="mr-auto">Information</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
        
            <div class="toast-body" id="toastmsgbody">
                <small>
                <p><h4>Holiday</h4></p>
                <p> Select month to find the holiday on selected month</p>
                    <br/>
                    <p><h4> Find Holiday </h4></p>
                    <p> Click button 'Find Holiday' to seacrh holiday on particular month</p>
                </small>
            </div>
        </div>
    <script src="./assets/scripts/js/mimo.js"></script>
</html>

