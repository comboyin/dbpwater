var friendListAction = function() {
	return {
		init : function() {
			//= = =  = = = = =  = = = = = begin unfriend  = = = = =  = = = = =  = = = = =  = = = = =  = = = = =  = = = = =  = =
				$("a.Unfriend").click( function(e){
					e.preventDefault();
					var media    = $(this).parents("div.media")[0];
					var tag_a    = $( 'a' , media )[0];
					var idfriend = $(tag_a).attr('idfriend');
					dalert.confirm( "Are You Sure?","Confirm !" , function( result ){
			            if( result ){
			            	unfriend( idfriend );
			            }
			            else{
			            	
			            }
			        });
				});
				
				function unfriend( idfriend ){
					
					var fd = new FormData();
					
					fd.append("UserId",idfriend);
					
					$.ajax({
				        url: 'index.php?rt=user/index/unfriend',
				        type: 'POST',
				        data : fd,
				        cache: false,
				        dataType: 'json',
				        processData: false, // Don't process the files
				        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
				        success: function(data, textStatus, jqXHR)
				        {
				        	dalert.alert("Unfriend success.","Success",function callbackMe(){
				        		loadListFriendList();
				            });
				        },
				        error: function(jqXHR, textStatus, errorThrown)
				        {
				        	var error = ['ERRORS: ' + textStatus];
				            // Handle errors here
				        	dalert.alert(stringHtmlError(error),'Error');
				        }
				    });
				}
				
				
				function loadListFriendList(){
					$.ajax({
				        url: 'index.php?rt=user/index/getHTMLListFriendRelation',
				        type: 'GET',
				        cache: false,
				        dataType: 'json',
				        processData: false, // Don't process the files
				        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
				        success: function(data, textStatus, jqXHR)
				        {
				        	var content = data.content;
				        	var total   = data.totalFriend;
				        	$("div.list-friend").html(content);
				        	$("span.total-friend").html(total);
				        	console.log(data);
				        },
				        error: function(jqXHR, textStatus, errorThrown)
				        {
				        	var error = ['ERRORS: ' + textStatus];
				            // Handle errors here
				        	dalert.alert(stringHtmlError(error),'Error');
				        }
				    });
				}
				
			//= = =  = = = = =  = = = = = end unfriend  = = = = =  = = = = =  = = = = =  = = = = =  = = = = =  = = = = =  = =
		}

	};
}();

jQuery(document).ready(function() {
	friendListAction.init();
});