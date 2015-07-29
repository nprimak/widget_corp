<?php 

function redirect_to($new_location) {
    header("Location: " . $new_location);
    exit;
}

function mysql_prep($string) {
    global $connection;
    
    $escaped_string = mysqli_real_escape_string($connection, $string);
    return $escaped_string;
    
}

function confirm_query($result_set){
 if(!$result_set) {
    die("Database query failed."); 
 }
}

function find_all_subjects($public=true) {
    global $connection;
    
    $query ="SELECT * ";
    $query .= "FROM subjects ";
    if($public){
        $query .= "WHERE visible = 1 ";
    }
    $query .= "ORDER BY position ASC";
    $subject_set = mysqli_query($connection, $query);
    confirm_query($subject_set); 
    return $subject_set;
}

function find_pages_for_subject($subject_id, $public=true) {
    global $connection;
    $subject_id = mysqli_real_escape_string($connection, $subject_id);
    
    $query ="SELECT * ";
    $query .= "FROM pages ";
    $query .= "WHERE subject_id = {$subject_id} ";
    if ($public){
        $query .= "AND visible = 1 ";
    }
    $query .= "ORDER BY position ASC";
    $page_set = mysqli_query($connection, $query);
    confirm_query($page_set);   
    return $page_set;
    
}

function find_page_by_id($page_id, $public=true) {
    global $connection;
    $page_id = mysqli_real_escape_string($connection, $page_id);
    
    $query ="SELECT * ";
    $query .= "FROM pages ";
    $query .= "WHERE id = {$page_id} ";
    if ($public) {
     $query .= "AND visible = 1 ";   
    }
    $query .= "LIMIT 1";
    $page_set = mysqli_query($connection, $query);
    confirm_query($page_set); 
    if ($page = mysqli_fetch_assoc($page_set)) {
        return $page;
    } else {
        return null;   
    }
    
}

function find_subject_by_id($subject_id, $public=true) {
    global $connection;
    $subject_id = mysqli_real_escape_string($connection, $subject_id);
    
    $query ="SELECT * ";
    $query .= "FROM subjects ";
    $query .= "WHERE id = {$subject_id} ";
    if ($public) {
     $query .= "AND visible = 1 ";   
    }
    $query .= "LIMIT 1";
    $subject_set = mysqli_query($connection, $query);
    confirm_query($subject_set); 
    if ($subject = mysqli_fetch_assoc($subject_set)) {
        return $subject;
    } else {
        return null;   
    }
}

function find_default_page_for_subject($subject_id) {
    $page_set = find_pages_for_subject($subject_id); 
     if ($first_page = mysqli_fetch_assoc($page_set)) {
        return $first_page;
    } else {
        return null;   
    }
    
}

function find_selected_page($public=false) {
    global $current_page;
    global $current_subject;
    
     if (isset($_GET["subject"])) { 
        $current_subject = find_subject_by_id($_GET["subject"], $public);
        $current_page = find_default_page_for_subject($current_subject["id"]);
    } elseif (isset($_GET["page"])) {
        $current_subject = null;
        $current_page = find_page_by_id($_GET["page"], $public);
    } else{
        $current_page = null;
        $current_subject = null;
    }   
}


function find_all_admin() {
    global $connection; 
    
    $query = "SELECT * ";
    $query .= "FROM admins ";
    $query .= "ORDER BY username ASC";
    $admin_set = mysqli_query($connection, $query);
    confirm_query($admin_set); 
    return $admin_set;
}

function find_admin_by_id($admin_id) {
    global $connection;
    $admin_id = mysqli_real_escape_string($connection, $admin_id);
    
    $query = "SELECT * ";
    $query .= "FROM admins ";
    $query .= "WHERE id = {$admin_id} ";
    $query .= "LIMIT 1";
    $admin_query = mysqli_query($connection, $query);
    confirm_query($admin_query); 
    return $admin_query;
}

function find_admin_by_username($username) {
    global $connection;
    $username = mysqli_real_escape_string($connection, $username);
    
    $query = "SELECT * ";
    $query .= "FROM admins ";
    $query .= "WHERE username = '{$username}' ";
    $query .= "LIMIT 1";
    $admin_query = mysqli_query($connection, $query);
    confirm_query($admin_query); 
    if($admin = mysqli_fetch_assoc($admin_query)){
        return $admin;   
    } else {
        return null;   
    }
}


// navigation takes two arguments
// current subject array OR null
// AND current page array OR null
function navigation($subject_array, $page_array) {
    $output = "<ul class=\"subjects\">";
    $subject_set = find_all_subjects(false);
    while($subject = mysqli_fetch_assoc($subject_set)) {
        $output .= "<li";
        if ($subject_array && $subject["id"] == $subject_array["id"]) {
            $output .= " class=\"selected\"";
        }
        $output .= ">";
        $output .= "<a href=\"manage_content.php?subject=";
        $output .= urlencode($subject["id"]);
        $output .= "\">";
        $output .= htmlentities($subject["menu_name"]);
        $output .= "</a></li>";
        $page_set = find_pages_for_subject($subject["id"], false);
        $output .= "<ul class=\"pages\">";
        while($page = mysqli_fetch_assoc($page_set)) { 
             $output .= "<li";
             if ($page_array && $page["id"] == $page_array["id"]) {
                 $output .= " class=\"selected\"";
              }
              $output .= ">";
              $output .= "<a href=\"manage_content.php?page=";
              $output .= urlencode($page["id"]);
              $output .= "\">";
              $output .= htmlentities($page["menu_name"]);
              $output .= "</a></li>"; 
  
         }   
         mysqli_free_result($page_set);
         $output .= "</ul>";
             
     }    
     mysqli_free_result($subject_set);
     $output .= "</ul>";
    return $output;
}

function public_navigation($subject_array, $page_array) {
    $output = "<ul class=\"subjects\">";
    $subject_set = find_all_subjects();
    while($subject = mysqli_fetch_assoc($subject_set)) {
        $output .= "<li";
        if ($subject_array && $subject["id"] == $subject_array["id"]) {
            $output .= " class=\"selected\"";
        }
        $output .= ">";
        $output .= "<a href=\"index.php?subject=";
        $output .= urlencode($subject["id"]);
        $output .= "\">";
        $output .= htmlentities($subject["menu_name"]);
        $output .= "</a></li>";
        if ($subject_array["id"] ==  $subject["id"] || $page_array["subject_id"] == $subject["id"]) {
            $page_set = find_pages_for_subject($subject["id"]);
            $output .= "<ul class=\"pages\">";
            while($page = mysqli_fetch_assoc($page_set)) { 
                $output .= "<li";
                if ($page_array && $page["id"] == $page_array["id"]) {
                 $output .= " class=\"selected\"";
                }
                $output .= ">";
                $output .= "<a href=\"index.php?page=";
                $output .= urlencode($page["id"]);
                $output .= "\">";
                $output .= htmlentities($page["menu_name"]);
                $output .= "</a></li>"; 
    
            }  
            $output .= "</ul>";
            mysqli_free_result($page_set);
        }
    $output .= "</li>"; //end of the subject
             
     }    
     mysqli_free_result($subject_set);
     $output .= "</ul>";
    return $output;
}




function attempt_login($username, $password) {
    //find user, then find password
    $admin = find_admin_by_username($username);
    if ($admin) {
        //found admin, now check password  
        if (password_verify($password, $admin["hashed_password"])){
            //password matches
            return $admin; 
        } else {
            return false;   
        }
        
    } else {
        //admin not found
        return false;
    }
    
}

function logged_in() {
    return isset($_SESSION['admin_id']);
}

function confirm_login() {
  if (!logged_in()) {
        redirect_to("login.php");   
  }   
}


function form_errors($errors= array()){
	$output = "";
	if (!empty($errors)) {
		$output = "<div id=\"error\">";
		$output .= "Please fix the following errors:";
		$output .= "<ul>";
		foreach ($errors as $key => $error) {
			$output .= "<li>";
            $output .= htmlentities($error);
            $output .= "</li>";
		}	
		$output .= "</ul>";
		$output .= "</div>";
	} 
	return $output;
}
	

?>