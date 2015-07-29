<?php require_once("../includes/session.php");?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php confirm_login(); ?>
<?php find_selected_page(); ?>
<?php 
 if(!$current_subject) {
     //must have subject id if we want to edit it
    redirect_to("manage_content.php");   
 }
?>
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
    
    if (empty($errors)) {
        //Perform Update 
    
        $id = $current_subject["id"];
        $menu_name = mysql_prep($_POST["menu_name"]);
        $position = (int) $_POST["position"];
        $visible = (int) $_POST["visible"];


        // Perform database query
        $query = "UPDATE subjects SET ";
        $query .= "menu_name = '{$menu_name}', ";
        $query .= "position = {$position}, ";
        $query .= "visible = {$visible} ";
        $query .= "WHERE id = {$id} ";
        $query .= "LIMIT 1";
        $result = mysqli_query($connection, $query);
        
        if($result && mysqli_affected_rows($connection) >= 0) {
            $_SESSION["message"] = "Subject Update Successful.";
            redirect_to("manage_content.php?");
        } else {
            //Failure
            $message = "Subject Update Failed.";
        }
    }
} else {
    // this is probably a GET request
}
?>
<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>
<main>
    <nav>
    <br /><a href="admin.php">&laquo; Main Menu</a><br />
      <?php echo navigation($current_subject, $current_page); ?>  
    </nav>
    <div id="page">
        <?php if(isset($message)) {
                $output = "<div id=\"message\">";
                $output .= htmlentities($message);
                $output .= "</div>";
                echo $output;
            } 
        ?>
        <h2>Edit Subject: <?php echo htmlentities($current_subject["menu_name"]); ?></h2>
        <?php echo form_errors($errors) ?>
        <form action="edit_subject.php?subject=<?php echo urlencode($current_subject["id"]) ;?>" method="post">
            <p>Menu name:
                <input type="text" name="menu_name" value="<?php echo htmlentities($current_subject["menu_name"]); ?>" />
            </p>
            <p>Position:
                <select name="position">
                    <?php
                        $subject_set = find_all_subjects(false);
                        $subject_count = mysqli_num_rows($subject_set); //tells # rows
                        for($count= 1; $count <= ($subject_count); $count++) {
                            echo "<option value=\"{$count}\"";
                            if($count == $current_subject["position"]){
                                echo " selected";
                            }
                            echo ">{$count}</option>";
                        }
                    ?>
                </select>
            </p>
            <p>Visible
                <input type="radio" name="visible" value="0" <?php if($current_subject["visible"] == 0) { echo "checked"; } ?>/>No
                &nbsp;
                <input type="radio" name="visible" value="1" <?php if($current_subject["visible"] == 1) { echo "checked"; } ?>/>Yes
            </p>
            <input type="submit" name="submit" value="Edit Subject" />
        </form>
        <br />
        <a href="manage_content.php">Cancel</a>
        &nbsp;
        &nbsp;
        <a href="delete_subject.php?subject=<?php echo urlencode($current_subject["id"])?>" onclick="return confirm('Are you sure?')">Delete Subject</a>
    </div>
</main>
<?php include("../includes/layouts/footer.php"); ?>
