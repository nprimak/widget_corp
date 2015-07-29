<?php 

$errors = array();

function fieldname_as_text($fieldname) {
    $fieldname = str_replace("_", " ", $fieldname);
    $fieldname = ucfirst($fieldname);
    return $fieldname;
    
}

//presence, is there a value present
function has_presence($value) {
	return isset($value) && $value !== "";
		
}

function validate_presence($required_fields) {
    global $errors;
    foreach($required_fields as $field) {
        $value = trim($_POST[$field]);
        if (!has_presence($value)) {
            $errors[$field] = fieldname_as_text($field) . " can't be blank";   
        }
    }
}


// max length
function has_max_length($value, $max){
		if(strlen($value) >= $max){
			return true;	
		}
}

//min length
function has_min_length($value, $min){
		if(strlen($value) <= $min){
			return true;	
		}
}


function includes_number($password){
    global $errors;
	$char_list = "0123456789";
	if(!strpbrk($password, $char_list)){
        $errors[] = "Password must include a number.";
	}
}

function includes_capital($password){
    global $errors;
	$char_list = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	if(!strpbrk($password, $char_list)){
        $errors[] = "Password must include a capital letter.";
	}
}



function validate_max_lengths($fields_with_max_lengths) {
	global $errors;
	//Using an assoc. array
	foreach($fields_with_max_lengths as $field => $max) {
		$value = trim($_POST[$field]);
		if (has_max_length($value,$max)) {
			$errors[] = fieldname_as_text($field) . " is too long";	
		}
	}
}

function validate_min_lengths($fields_with_min_lengths) {
	global $errors;
	//Using an assoc. array
	foreach($fields_with_min_lengths as $field => $min) {
		$value = trim($_POST[$field]);
		if (has_min_length($value,$min) && $value !== "" && isset($value)) {
			$errors[] = fieldname_as_text($field) . " is too short";	
		}
	}
}


?>
