let filename;

$(document).ready(function () {
    //location.reload(true);
    $('#btncalculate').hide();
    $('#btnexport').hide();
    
    $('#toasttat').toast('show');
   
   
   
   
    $filename='';
    $('#param').click(function (e) { 
        
        window.open('./assets/scripts/ajax/import_item.php','_self');    
    });
    $('#tat').click(function (e) { 
        
        window.open('tat.php','_self');
           
    });
    $('#fileexcel').change(function(e){
         filename = e.target.files[0].name;
         console.log(filename);
         $('#txtfile').val(filename);
         
    });

    $('#holiday').click(function (e) { 
        window.open('holiday.php','_self'); 
    });

    $('#uploadbtn').click(function(e){
        console.log('filename is: ' + filename);
        if(filename == '' ){
            swal({
                title: "Choose File First",
                text: "Please choose file",
                timer: 3000,
                type: "error",
                showConfirmButton: false
            });
            filename='';
        }else{
            var fd = new FormData();
            var files = $('#fileexcel')[0].files;
            // Check file selected or not
            fd.append('file',files[0]);
            // var fd = new FormData();
            // fd.append('ft',filename);
            $.ajax({
				type: "POST",
				url:  "./assets/scripts/ajax/tat_file.php",
				data: fd,
                contentType:false,
                cache:false,
                processData:false,
                success : function(response){
                    console.log('filename php : ' + filename);
                    $('#importresult').html(response);
                    $('#btncalculate').show();
                }
            });           
        }
    })

    $('#btncalculate').click(function(e){
       $.blockUI();
        $.ajax({
            type: "POST", 
            url: "./assets/scripts/ajax/calculate_real.php",
            data:'',
            
            success: function(mimoresponse){
                $('#importresult').html('');
                $('#btncalculate').hide();
                $('#importresult').html(mimoresponse);
                $('#btnexport').show();
            },
            complete : function (){
                $.unblockUI();
            }
        })
    });
    
    $('#btnexport').click(function(e){
        $("#fileModal").modal("show", { backdrop: "static" });
        $('#h1-1').html('Export To Excel .XLSX');
    });

    $('#btnexportexcel').click(function(e){
        //$("#fileModal").modal("show", { backdrop: "static" });
        var header1 = $('#txtheader1').val();
        var header2 = $('#txtheader2').val();
        var filename1 = $('#txtfilename').val();
        if(header1==''){
            header1 = 'TAT REPORTS';
        }

        if(header2==''){
            header2 = 'FOR THE PERIOD OF';
        }

        if(filename1==''){
            filename1 = 'tat-lab';
        }

        $.ajax({
            type: "POST",
            url: "./assets/scripts/ajax/export.php",
            data:"header1="+header1+"&header2="+header2+"&filename="+filename1,
            success: function(lasagna){
                $.ajax({
                    type:"POST",
                    url:"./assets/scripts/ajax/checkfile.php",
                    data:"filename="+filename1,
                    success: function(responsefile){
                        if(responsefile=='exist'){
                            swal({
                                title: "File Exported Succesfully",
                                text: "File is ready",
                                timer: 3000,
                                type: "success",
                                showConfirmButton: false
                            });
                        }else{
                            swal({
                                title: "File Did Not Exported",
                                text: "File Error",
                                timer: 3000,
                                type: "danger",
                                showConfirmButton: false
                            });
                        }
                    } 
                })
                
            }
        })
        $("#fileModal").modal("hide");
    });

    $("#findholiday").click(function (e) { 
        selectedmonth = $('#_month').val();
        $.ajax({
            type: "POST",
            url: "./assets/scripts/ajax/holiday_api.php",
            data:"mymonth="+selectedmonth,
            success: function(lasagna){
                console.log(lasagna);
                    $('#resultholiday').html(' ');
                    $("#resultholiday").html(lasagna);
            }
        })
     })
});
