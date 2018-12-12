// Toggle Function
jQuery(document).ready(function($){
    var modalConfirm = function(callback){
        $(".btn-import").on("click", function(){
            $("#confirmImportModal").modal('show');
        });

        $(".modal-btn-yes").on("click", function(){
            callback(true);
            $("#confirmImportModal").modal('hide');
        });

        $(".modal-btn-no").on("click", function(){
            callback(false);
            $("#confirmImportModal").modal('hide');
        });
    };
    modalConfirm(function(confirm){
        if(confirm){
            //$("#result").html("CONFIRM Yes");
            $("#importDB").submit();
        }else{
            //$("#result").html("Confirm No");
        }
    });

    $('#importDB').validate({
        rules: {
            ip: {
                required: true
            },
            user: {
                required: true
            },
            password: {
                required: true
            },
            dbname: {
                required: true
            },
            env: {
                required: true
            }
        },
        messages: {
            ip: "Invalid input",
            user: "Invalid input",
            password: "Invalid input",
            dbname: "Invalid input"
            //env: "Invalid input"
        },
        submitHandler: function() {
            //alert('aaaa');
            $('.overlay').show();
            url_ajax = 'importdb/index/importData';
            $.ajax({
                url: url_ajax,
                //async: false,
                type : "POST",
                dataType : 'json',
                //dataType : 'html',
                cache: false,
                data : $("#importDB").serialize(),
                success : function(result) {
                    console.log(result);
                    if (result.error == 0) {
                        msg = '<div class="alert alert-success alert-dismissible">' +
                            '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>' +
                                result.content +
                            '</strong></div>';
                    } else {
                        msg = '<div class="alert alert-danger alert-dismissible">' +
                                '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>' +
                                result.content +
                            '</strong></div>';
                    }
                    $("#result").html(msg);
                    $('.overlay').hide();
                },
                error: function(xhr, status, error){
                    var errorMessage = xhr.status + ': ' + xhr.statusText
                    //alert('Error - ' + errorMessage);
                    alert('Something went wrong ' + status + ' - ' + error);
                }
            });
            //form.submit();
        }
    });
});