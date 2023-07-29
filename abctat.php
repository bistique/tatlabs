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
                <p class="card-text"><small class="text-muted">Tools to update paramater data</small></p>
                </div>
            </div>
            <div id="tat" class="card bg-primary ">
                <div class="card-body">
                <h5 class="card-text text-white" style="font-size:30px;">T A T</h5>
                <p class="card-text text-white"><small>Process TAT</small></p>
            </div>
        </div>
        
    </body>
    <!-- <div  aria-live="polite" aria-atomic="true" style="position: relative; min-height: 200px;"> -->
        <div id="toastmenu" class="toast" style="position: absolute; top: 0; right: 0;" data-autohide="false">
            <div class="toast-header">
                <img src="infologo.png" class="rounded mr-2" alt="..." width="32px">
                <strong class="mr-auto">Information</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
         </div>
            <div class="toast-body" id="toastmsgbody">
                <small>
                    <p><h4>Parameter Database</h4></p>
                    <p> You can add new parameter to database by automatically importing from Ms.Excel</p>
                    <br/>
                    <p><h4> TAT Process</h4></p>
                    <p> Process TAT by importing Excel File then calculate automatically</p>
                    
                </small>
            </div>
        </div>
    <!-- </div> -->
    <script src="./assets/scripts/js/mimo.js"></script>
</html>
