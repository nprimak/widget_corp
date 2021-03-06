<?php require_once("../includes/session.php");?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php 
if (isset($_POST['submit'])) {
    //Process the form 
    
    // Validations
    $required_fields = array("menu_name", "position", "visible");
    validate_presence($required_fields);
    
    $fields_with_max_lengths = array("menu_name" => 30);
    validate_max_lengths($fields_with_max_lengths);
    
    $fields_with_min_lengths = array("menu_name" => 3);
   validate_min_lengths($fields_with_min_lengths);
    
    if (!empty($errors)) {
        $_SESSION["errors"] = $errors;
        redirect_to("new_subject.php");
    } 
    
    $menu_name = mysql_prep($_POST["menu_name"]);
    $position = (int) $_POST["position"];
    $visible = (int) $_POST["visible"];


    //2. Perform database query
    $query ="INSERT INTO subjects (";
    $query .= " menu_name, position, visible";
    $query .= ") VALUES (";
    $query .= " '{$menu_name}', {$position}, {$visible}";
    $query .= ")";

    $result = mysqli_query($connection, $query);
    if($result) {
        $_SESSION["message"] = "Subject created.";
        redirect_to("manage_content.php?{$message}");
    } else {
        //Failure
        $_SESSION["message"] = "Subject creation failed";
        redirect_to("new_subject.php?{$message}");   
    }

} else {
    // this is probably a GET request
    redirect_to("new_subject.php");
}
?>
<?php 
    if (isset($connection)){    mysqli_close($connection); }
?>