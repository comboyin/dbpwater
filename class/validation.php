<?php
class validation {

	public function between($string,$option = array()){

		$min = isset($option['min'])? $option['min'] : 0;
		$max = isset($option['max'])? $option['max'] : 10;

		if(strlen($string) < $min || strlen($string) > $max){
			$error[] = "Value between $min and $max.";
		}

		return isset($error)?$error:null;
	}

	public function checkSymbol($string){
		$flag = true;
		$error = null;
		if ( preg_match ( '/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $string ) ) {
			$flag = false;
		}

		if( $flag == false ){
			$error[] = 'Value include the characters a-Z and number';
		}

		return $error;
	}


	public function checkString($string) {
	    $error = null;
	    $flag = true;

	    if ( preg_match ( '/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $string ) ) {
	        $flag = false;
	    }

	    for($i = 0; $i < strlen ( $string ); $i ++) {

	        if (is_numeric ( $string [$i] )) {

	            $flag = false;
	            break;

	        }
	    }

	    if( $flag == false ){
	        $error[] = 'Value include the characters a-Z';
	    }

	    return $error;
	}

	public function isNumberAndBetween($number,$option = array()){

		$min = isset($option['min'])? $option['min'] : 0;
		$max = isset($option['max'])? $option['max'] : 1000000000;
		if(!is_numeric($number)){
			$error[] = "Value is not numberic.";
			$error[] = "Number between $min and $max";
		}else{
			if($number < $min || $number > $max){
				$error[] = "Number between $min and $max";
			}
		}

		return isset($error)?$error:null;
	}

	public function isNumber($number){

		if(!is_numeric($number)){
			$error[] = "Value is not numberic.";
		}

		return isset($error)?$error:null;
	}

	public function checkEmpty($string){
		if(strlen($string) == 0){
			$error[] = "Value is empty.";
		}
		return isset($error) ? $error : null;
	}

	public function fileImageValidation( $file , $option = array()){

		if( !isset( $file['name'] ) || strlen( $file['name'] ) == '' ){
			$error[] = "Image not selected.";
			return $error;
		}

		$validation_max_file_size = isset( $option['size'] ) ? $option['size'] : 140000 ;


		$target_dir = __FOLDER_UPLOADS . "/";
		$path_parts = pathinfo(basename($file["name"]));
        $fileName = $path_parts['filename'].uniqid().'.'.$path_parts['extension'];
		$target_file = $target_dir.$fileName;

		$imageFileType = $path_parts['extension'];

		if (isset($_POST["submit"])) {
			$check = getimagesize($file["tmp_name"]);
			if ($check === false) {
				$error[] = "File is not an image.";
			}
		}

		if (file_exists($target_file)) {

			$error[] = "Sorry, file already exists.";
		}

		if ($file["size"] > $validation_max_file_size) {
			$kb = $validation_max_file_size/1000;
			$error[] = "Sorry, your file is too large. File smaller $kb KB.";
		}

		if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {

			$error[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		}
		if(!isset($error)){
		    if (move_uploaded_file( $file["tmp_name"] , $target_file ) ) {

		    }
		    else{
		        $error[] = "Sorry, there was an error uploading your file.";
		    }
		}


		return isset( $error ) ? $error : $fileName;
	}
}