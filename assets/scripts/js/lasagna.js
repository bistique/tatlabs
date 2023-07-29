$(document).ready(function(){
    $('#toastlogin').toast('show');
    $('#submit').click(function(e){
        var usr_=$('#username').val();
        var pwd_=$('#pwd').val();
        if(usr_!='' || pwd_!=''){
            $.ajax({
                type: "POST",
                url: "./assets/scripts/ajax/____login.php",
                data: "username="+usr_+"&pwd="+pwd_,
                success: function (response) {
                    if(response=='ok'){
                        window.open('../../../abctat.php','_self');
                    }
                }
            });
        }else{
            
        }
    })

})