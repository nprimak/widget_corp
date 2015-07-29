<?php require_once("../includes/session.php");?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php confirm_login(); ?>
<?php $layout_context = "admin"; ?>
<?php 
    $id = (int) $_GET["id"];
    if(!$id) {
        //must have subject id if we want to edit it
        redirect_to("list_admins.php");   
    }
    $admin = mysqli_fetch_assoc(find_admin_by_id($id));

?>
<?php 
    if (isset($_POST["submit"])){
        $required_fields = array("username", "password");
        validate_presence($required_fields);
        
        $fields_with_max_lengths = array("username" => 30 , "password" => 30);
        validate_max_lengths($fields_with_max_lengths);
        
        $fields_with_min_lengths = array("username" => 5 , "password" => 5);
        validate_min_lengths($fields_with_min_lengths);
        
        $password = $_POST["password"];
        includes_number($password);
        includes_capital($password);
    
        if(empty($errors)){
            $username = mysql_prep($_POST["username"]);
            $hashed_password = password_hash($password, PASSWORD_BCRYPT, [cost => 10]);
        
            $query = "UPDATE admins SET ";
            $query .= "username = '{$username}', ";
            $query .= "hashed_password = '{$hashed_password}' ";
            $query .= "WHERE id = {$id}";
            
            $result = mysqli_query($connection, $query);
            
            if($result  && mysqli_affected_rows >= 0) {
                $_SESSION["message"] = "User Edited.";
                redirect_to("list_admins.php");
            } else {
                $message = "User Edit Failed." ;
            }
        }
    }
?>
<?php include("../includes/layouts/header.php"); ?>
<main>
    <nav><br>
        <a href="admin.php">&laquo; Main Menu</a></nav>
    <div id="page">
    <h2>Add New User</h2>
        <?php if(isset($message)) {
                $output = "<div id=\"message\">";
                $output .= htmlentities($message);
                $output .= "</div>";
                echo $output;
            } 
        ?>
        <?php echo form_errors($errors); ?>
        <form action="edit_admin.php?id=<?php echo urlencode($id);?>" method="post">
            Username: <input type="text" name="username" value="<?php echo htmlentities($admin["username"]) ;?>">
            <br>
            <br>
            Password: <input type="password" name="password" >
            <br>
             <br>
            <input type="submit" name="submit" value="Edit User">
        </form>
        <br>
        <a href="list_admins.php">Cancel</a>
       
    </div>
</main>
<?php include("../includes/layouts/footer.php"); ?>