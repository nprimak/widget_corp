<?php require_once("../includes/session.php");?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php confirm_login(); ?>
<?php $layout_context = "admin"; ?>
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
        
            $query = "INSERT INTO admins (";
            $query .= " username, hashed_password ";
            $query .= ") VALUES (";
            $query .= " '{$username}', '{$hashed_password}' ";
            $query .= ")";
            
            $result = mysqli_query($connection, $query);
            
            if($result) {
                $_SESSION["message"] = "New User Created.";
                redirect_to("list_admins.php");
            } else {
                $message = "User Creation Failed." ;
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
        <form action="new_admin.php" method="post">
            Username: <input type="text" name="username">
            <br>
            <br>
            Password: <input type="password" name="password" >
            <br>
             <br>
            <input type="submit" name="submit" value="Create User">
        </form>
        <br>
        <a href="list_admins.php">Cancel</a>
        <?php //phpinfo() ?>
    </div>
</main>
<?php include("../includes/layouts/footer.php"); ?>