

var indexAction = function () {
	return {
		init : function () {
			dialogAddPicture = $("#dialog-add-list-picture").dialog({
				autoOpen: false,
				modal: true,
			    maxHeight: 600,
			    maxWidth: 600
			});
			// == == == == == == == == == == begin add picture == == == == == == == == == == == == == == == == == == == == ==
			$("div.add-picture").click(function(e){
				e.preventDefault();
				dialogAddPicture.dialog('open');
			});
			
			function resetListPicture(){
				$.ajax({
			        url: 'index.php?rt=user/index/getHtmlListPicture',
			        type: 'GET',
			        cache: false,
			        dataType: 'json',
			        processData: false, // Don't process the files
			        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
			        success: function(data, textStatus, jqXHR)
			        {
			        	var content = data.content;
			        	
			        	$("span.listPicture").html( content );
			        },
			        
			        
			        error: function(jqXHR, textStatus, errorThrown)
			        {
			        	var error = ['ERRORS: ' + textStatus];
			            // Handle errors here
			        	dalert.alert(stringHtmlError(error),'Error');
			        }
			    });
			}
			
			function addPicture(){
				
				$(".progress-loading-picture").css("display",'inline-block');
				
				$("input[name=submit_add_picture]").css("display",'none');
				
				fd = new FormData();
				// list image
				$.each( $('input[name="pictures[]"]') ,function(key,value){
					var files = value.files;
					$.each(files,function(keyF,valueF){
						fd.append("pictures[]",valueF);
					});
				});
				
				$.ajax({
			        url: 'index.php?rt=user/index/addPictures',
			        type: 'POST',
			        data: fd,
			        cache: false,
			        dataType: 'json',
			        processData: false, // Don't process the files
			        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
			        success: function(data, textStatus, jqXHR)
			        {
			        	
			        	$(".progress-loading-picture").css("display",'none');
						$("input[name=submit_add_picture]").css("display",'inline-block');
			        	var htmlError = '';
			        	// error
			        	if(data.is_error != null){
			        		htmlError = generateHtmlAlertError( data.is_error );
			        		$("div.error_picture").html(htmlError);
			        	}else{
			        		$("div.error_picture").html('');
			        		// remove image.
			        		$( 'input[name="pictures[]"]' ).MultiFile( 'reset' );
			        		// close dialog
			        		dialogAddPicture.dialog( 'close' );
			        		
			        		dalert.alert("Add pictures success.","Success",function callbackMe(){
			        			resetListPicture();
			                });
			        	}
			        },
			        
			        
			        error: function(jqXHR, textStatus, errorThrown)
			        {
			        	var error = ['ERRORS: ' + textStatus];
			            // Handle errors here
			        	dalert.alert(stringHtmlError(error),'Error');
			        }
			    });
			}
			
			$("input[name=submit_add_picture]").click(function(e){
				e.preventDefault();
				addPicture();
			});
			// == == == == == == == == == == end add picture == == == == == == == == == == == == == == == == == == == == ==
			
			
			
			
			// == == == == == == == == == == begin Delete picture == == == == == == == == == == == == == == == == == == == == ==
			$(document).on( 'click' , 'a[data-original-title=Delete]' , function(e){
					e.preventDefault();
					var idpicture = $(this).attr('id-picture');
			        dalert.confirm("Are You Sure?","Alert Confirm !",function(result){
			            if(result){
			            	deletePicture( idpicture );
			            }
			            else{
			            	
			            }
			        });
				});
			
			function deletePicture( idpicture ){
				fd = new FormData();
				fd.append("idpicture", idpicture );

				$.ajax({
			        url: 'index.php?rt=user/index/deletePicture',
			        type: 'POST',
			        data: fd,
			        cache: false,
			        dataType: 'json',
			        processData: false, // Don't process the files
			        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
			        success: function(data, textStatus, jqXHR)
			        {
			        	var is_error = data.is_error;
			        	if( is_error == null ){
			        		// success
			        		dalert.alert("Delete picture success.","Success",function callbackMe(){
			        			resetListPicture();
			                });	
			        		
			        	}else{
			        		// error
			        		generateHtmlAlertError( is_error );
			        	}
			        },
			        error: function(jqXHR, textStatus, errorThrown)
			        {
			        	var error = ['ERRORS: ' + textStatus];
			            // Handle errors here
			        	dalert.alert(stringHtmlError(error),'Error');
			        }
			    });
			}
			
			// == == == == == == == == == == end delete picture == == == == == == == == == == == == == == == == == == == == ==
		}

	};
}();


jQuery(document).ready(function () {
	indexAction.init();
});