// Toggle Function
jQuery(document).ready(function($){

    $('select').niceSelect();

    $(".btn-import").on("click", function(){
        $('.overlay').show();
        $.ajax({
            url: 'importdb/index/checkDatabase',
            //async: false,
            type : "POST",
            dataType : 'json',
            //dataType : 'html',
            cache: false,
            data : $("#importDB").serialize(),
            success : function(result) {
                console.log(result);
                if (result.check_database) {
                    $('#checkDatabase').val('1');
                    $("#checkDatabaseModal").modal('show');
                    // $("#checkDbYes").on('click', function(){
                    //     console.log('submit1');
                    //     $("#checkDatabaseModal").modal('hide');
                    //     $("#importDB").submit();
                    // });
                    // $(".check-db-btn-no").on('click', function(){
                    //     $("#checkDatabaseModal").modal('hide');
                    // });
                } else {
                    $('#checkDatabase').val('0');
                    $("#confirmImportModal").modal('show');
                    // $("#btnConfirmYes").on('click', function(){
                    //     $("#confirmImportModal").modal('hide');
                    //     console.log('submit0');
                    //     $("#importDB").submit();
                    // });
                    // $(".modal-btn-no").on('click', function(){
                    //     $("#confirmImportModal").modal('hide');
                    // });
                }
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
    });

    $(".check-db-btn-yes").on('click', function(){
        console.log('submit1');
        $("#checkDatabaseModal").modal('hide');
        $("#importDB").submit();
    });

    $(".modal-btn-yes").on('click', function(){
        $("#confirmImportModal").modal('hide');
        console.log('submit0');
        $("#importDB").submit();
    });

    // var modalConfirm = function(callback){
    //     $(".btn-import").on("click", function(){
    //         $("#confirmImportModal").modal('show');
    //     });
    //     $(".modal-btn-yes").on("click", function(){
    //         callback(true);
    //         $("#confirmImportModal").modal('hide');
    //     });
    //     $(".modal-btn-no").on("click", function(){
    //         callback(false);
    //         $("#confirmImportModal").modal('hide');
    //     });
    // };
    // modalConfirm(function(confirm){
    //     if(confirm){
    //         //$("#result").html("CONFIRM Yes");
    //         $("#importDB").submit();
    //     }else{
    //         //$("#result").html("Confirm No");
    //     }
    // });
    $('#dbname').on('keypress', function(){
        if ($(this).val().length > 60) {
            return false;
        }
    });
    $.validator.addMethod('validName', function(value, element, param){
        var dbname = $('#dbname').val();
        var reg = new RegExp("^[a-zA-Z0-9_]+$");
        if (reg.test(dbname)) {
            return true;
        }
        return false;

    }, 'Invalid name');
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
                required: true,
                validName: true
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
            console.log('submit form');
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