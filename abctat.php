<?php
require_once './lib/config.php';
require_once './lib/header.php';
?>
<!DOCTYPE HTML>
<html>
    <head>
    </head>
    <body>
        <div id="divimage" class="mt-3 ml-3">
            <img src="logo.png" width="100px" height="40px">
        </div>
        <div class="card-deck mt-5" style="margin-left:20%;margin-right:20%;">
            <div id="param" class="card">
                <div class="card-body bg-warning">
            
                <h5 class="card-text" style="font-size:30px;">Parameter Database</h5>
                <p class="card-text"><medium class="text-muted">Tools to update paramater data</medium></p>
                </div>
            </div>
            
            <div id="tat" class="card bg-primary ">
                <div class="card-body">
                    <h5 class="card-text text-white" style="font-size:30px;">T A T</h5>
                    <p class="card-text text-white"><medium>Process TAT</medium></p>
                </div>
            </div>

            <div id="holiday" class="card bg-danger ">
                <div class="card-body">
                    <h5 class="card-text text-white" style="font-size:30px;">Tools-Holiday</h5>
                    <p class="card-text text-white"><medium>Find Holiday...</medium></p>
                </div>
            </div>
        </div>
        
    </body>
    <!-- <div  aria-live="polite" aria-atomic="true" style="position: relative; min-height: 200px;"> -->
    <div id="toasttat" class="toast" style="position: absolute; top: 0; right: 0;" data-autohide="false">
                <img src="infologo.png" class="rounded mr-2" alt="..." width="32px">
                <strong class="mr-auto">Information</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
         
            <div class="toast-body" id="toastmsgbody">
                <medium>
                <p><h4>Choose File</h4></p>
                <p> Click button 'Choose File' then select Excel file located in your folder</p>
                    <br/>
                <p><h4>Process</h4></p>
                <p> Click button 'Process' to import file excel to the screen</p>
                <br/>
                <p><h4> Tools-Holiday </h4></p>
                <p>A tool to give you information about holiday </p>
                    
                </medium>
            </div>
    </div>
    <!-- </div> -->
    <script src="./assets/scripts/js/mimo.js"></script>
</html>
