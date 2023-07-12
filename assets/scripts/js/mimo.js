let filename;
$(document).ready(function () {
    $('#btncalculate').hide();
    $filename='';
    $('#param').click(function (e) { 
        console.log('param');
        window.open('./assets/scripts/ajax/import_item.php','_self');    
    });
    $('#tat').click(function (e) { 
        console.log('param');
        window.open('tat.php','_self');
           
    });
    $('#fileexcel').change(function(e){
         filename = e.target.files[0].name;
         console.log(filename);
         $('#txtfile').val(filename);
         
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
});
//var foto = $('#filefoto').prop("files")[0];
// var fd = new FormData();
// fd.append('ft',foto);
// // type : "POST",
// url: path+"saveproduct",
// data: fd,
// contentType: false,
// cache: false,
// processData: false,
// success: function(response){
// }