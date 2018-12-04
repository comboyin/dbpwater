// Toggle Function
jQuery(document).ready(function($){
    var modalConfirm = function(callback){
        $(".btn-import").on("click", function(){
            $("#importDB").on('submit', function(event){
                 alert('submit');
            //     event.preventDefault();
            });
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
            //Acciones si el usuario confirma
            $("#result").html("CONFIRM Yes");
            $("#importDB").submit();
        }else{
            //Acciones si el usuario no confirma
            $("#result").html("Confirm No");
        }
    });
});