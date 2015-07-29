<?php require_once("../includes/session.php");?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php 
    $id = (int) $_GET["id"];
    if(!$id) {
        //must have subject id if we want to edit it
        redirect_to("list_admins.php");   
    }
 $query = "DELETE FROM admins WHERE id = {$id} LIMIT 1;";
 $result = mysqli_query($connection, $query);
 
 if ($result && mysqli_affected_rows($connection) == 1) {
     $_SESSION["message"] = "User deleted.";
     redirect_to("list_admins.php?");
 } else {
     $_SESSION["message"] = "User deletion failed.";
     redirect_to("list_admins.php");
 }
?>
