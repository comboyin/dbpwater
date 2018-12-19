// Toggle Function
jQuery(document).ready(function($){

    $('select').niceSelect();

    $('#dbname').on('keypress', function(){
        if ($(this).val().length > 60) {
            return false;
        }
    });

    $('.btn-export').on('click', function(){
        $("#confirmExportModal").modal('show');
    });

    $(".modal-btn-yes").on('click', function(){
        $("#confirmExportModal").modal('hide');
        console.log('submit0');
        $("#exportDB").submit();
    });

    $.validator.addMethod('validName', function(value, element, param){
        var dbname = $('#dbname').val();
        var reg = new RegExp("^[a-zA-Z0-9_]+$");
        if (reg.test(dbname)) {
            return true;
        }
        return false;

    }, 'Invalid name');
    $('#exportDB').validate({
        rules: {
            host: {
                required: true
            },
            user: {
                required: true
            },
            password: {
                required: true
            },
            dbname: {
                required: true,
                validName: true
            },
            env: {
                required: true
            }
        },
        messages: {
            host: "Invalid input",
            user: "Invalid input",
            password: "Invalid input",
            dbname: "Invalid input"
            //env: "Invalid input"
        },
        submitHandler: function() {
            console.log('submit form');
            $('.overlay').show();
            url_ajax = 'exportdb/index/exportData';
            $.ajax({
                url: url_ajax,
                //async: false,
                type : "POST",
                dataType : 'json',
                //dataType : 'html',
                cache: false,
                data : $("#exportDB").serialize(),
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
                    msg = '<div class="alert alert-danger alert-dismissible">' +
                        '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>' +
                            'Something went wrong ' + status + ' - ' + error +
                        '</strong></div>';
                    $("#result").html(msg);
                    $('.overlay').hide();
                }
            });
            //form.submit();
        }
    });
});