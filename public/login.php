<?php require_once("../includes/session.php");?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php $layout_context = "admin"; ?>
<?php 
$username = "";

    if (isset($_POST["submit"])){
        $required_fields = array("username", "password");
        validate_presence($required_fields);
        
    
        if(empty($errors)){
            //Attempt Login 
            $username = $_POST["username"];
            $password = $_POST["password"];
            
           
            
            $found_admin = attempt_login($username, $password);
            
            if($found_admin) {
                //Success
                //Mark user as logged in
                $_SESSION["admin_id"] = $found_admin["id"];
                $_SESSION["username"] = $found_admin["username"];
                redirect_to("admin.php");
            } else {
                //Failure
                $message = "Username/password incorrect." ;
            }
        }
    }
?>
<?php include("../includes/layouts/header.php"); ?>
<main>
    <nav><br>
        <a href="index.php">&laquo; Home</a></nav>
    <div id="page">
    <h2>Login</h2>
         <?php if(isset($message)) {
                $output = "<div id=\"message\">";
                $output .= htmlentities($message);
                $output .= "</div>"; 
                echo $output;
            } 
        ?>
        <?php echo form_errors($errors); ?>
        <form action="login.php" method="post">
            Username: <input type="text" name="username" value="<?php echo htmlentities($username); ?>">
            <br>
            <br>
            Password: <input type="password" name="password" >
            <br>
             <br>
            <input type="submit" name="submit" value="Submit">
        </form>
        <br>
    </div>
</main>
<?php include("../includes/layouts/footer.php"); ?>