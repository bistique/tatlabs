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
                <img src="excel.png" id="logoexcel" width="48px">
                <span>
                    <label>Please choose Excel File</label>
                </span>
            </div>
            <div class="mt-1 text-center">
                <span>
                    <input class="input-text" type="text" id="txtfile" readonly />
                </span>
                <input type="file" id="fileexcel" />
            </div>
            <div class=" mt-3 text-center">
                <button class="btn btn-success text-right" id="uploadbtn">Process</button>
            </div>
        </div>
        <div class="mt-4 mr-3 text-right">
            <button class="btn btn-primary" id="btncalculate">Calculate</button>
            <button class="btn btn-warning" id="btnexport">Export To Excel</button>
        </div>

        <div class="mt-4" id="importresult"></div>

        <div class="modal fade" id="fileModal" tabindex="-1" data-backdrop="false" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h1 class="modal-title" id="h1-1"></h1>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div id="message"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <label class="control-label" style="margin-left:10px;margin-top:5px;">Header Text 1 :</label>
                                <input type="text" id="txtheader1" class="form-control" maxlength="200" style="width:300px;margin-left:46px;" >
                            </div>
                            <div class="row mt-1">
                                <label class="control-label" style="margin-left:10px;margin-top:5px;">Header Text 2 :</label>
                                <input type="text" id="txtheader2" class="form-control" maxlength="200" style="width:300px;margin-left:46px;" >
                            </div>
                            <div class="row mt-1">
                                <label class="control-label" style="margin-left:10px;margin-top:5px;">Filename :</label>
                                <input type="text" id="txtfilename" class="form-control" maxlength="200" style="width:300px;margin-left:46px;" >
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" id="btnexportexcel" class="btn btn-success" data-dismiss="modal">Export</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
	    </div>
                
    </body>
    <script src="./assets/scripts/js/mimo.js"></script>
</html>

