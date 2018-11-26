function stringHtmlError( errorArray ){
	// data error
	htmlError = '<div class="alert alert-error">';
	$.each(errorArray,function(k,v){
		htmlError += v + "</br>";
	});
	htmlError += '</div>';
	return htmlError;
}

function formatNumber (num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}


//create html error alert
// error = array.
function generateHtmlAlertError( error ){
	var htmlError = " ";
	htmlError += '<div class="alert alert-danger"';
	htmlError += '<strong>Error! </strong>';
	$.each(error,function(keyName,valueName){
		htmlError += '<div class="item">';
		htmlError += '<p>';
		htmlError += '<strong>'+keyName+'</strong>';
		htmlError += '</p>';
		htmlError += '<ul>';

		$.each(valueName,function(keyError,valueError){
			if(typeof(valueError) == 'object'){
				htmlError += '<p>';
    			htmlError += '<strong>'+keyError+'</strong>';
    			htmlError += '</p>';
				$.each(valueError,function(keyListImage,valueListImage){
					htmlError += '<li>';
    				htmlError += valueListImage;
    				htmlError += '</li>';
				});
			}else{
				htmlError += '<li>';
				htmlError += valueError;
				htmlError += '</li>';
			}
		});
		htmlError += '</ul>';
	});
	htmlError += '</div>';
	return htmlError;
}
