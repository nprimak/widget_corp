<?php require_once("../includes/session.php");?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>
<?php confirm_login(); ?>
<?php find_selected_page(); ?>
<?php
    if(!$current_page) { 
        redirect_to("manage_content.php");   
    }
?>
<?php
    if (isset($_POST["submit"])){
        $required_fields = array("menu_name", "position", "visible", "content");
        validate_presence($required_fields);
        
        $fields_with_max_lengths = array("menu_name" => 30 , "content" => 5000);
        validate_max_lengths($fields_with_max_lengths);
        
        $fields_with_min_lengths = array("menu_name" => 3 , "content" => 25);
        validate_min_lengths($fields_with_min_lengths);
        
        if(empty($errors)) {
            $id = (int) $current_page["id"];
            $menu_name = mysql_prep($_POST["menu_name"]);
            $position = (int) $_POST["position"];
            $visible = (int) $_POST["visible"];
            $content = mysql_prep($_POST["content"]);
    
        
            $query ="UPDATE pages SET ";
            $query .= "menu_name = '{$menu_name}', ";
            $query .= "position = {$position}, ";
            $query .= "visible = {$visible}, ";
            $query .= "content = '{$content}' ";
            $query .= "WHERE id = {$id}";

            $result = mysqli_query($connection, $query);
            
            if($result && mysqli_affected_rows >= 0 ) {
                $_SESSION["message"] = "Page updated.";
                redirect_to("manage_content.php");
            } else {
                $message = "Page update failed." ;
            }
        } 
    }
?>
<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>
<main>
    <nav>
    <br />
    <a href="admin.php">&laquo; Main Menu</a><br />
      <?php echo navigation($current_subject, $current_page); ?>  
      <br />
      <a href="new_subject.php">+ Add a subject</a>
    </nav>
    <div id="page">
        <h2>Edit Page: <?php echo htmlentities($current_page["menu_name"]); ?></h2>   
           <?php if(isset($message)) {
                $output = "<div id=\"message\">";
                $output .= htmlentities($message);
                $output .= "</div>";
                echo $output;
            } 
        ?>

        <?php echo form_errors($errors); ?>
        <br>
        <form action="edit_page.php?page=<?php echo urlencode($current_page["id"]); ?>" method="post">
            Page Name: <input type="text" name="menu_name" value="<?php echo $current_page["menu_name"]; ?>">
            <br>
            <br>
            
              Position: <select name="position"> <?php
                        $page_set = find_pages_for_subject($current_page["subject_id"], false);
                        $page_count = mysqli_num_rows($page_set); //tells # rows
                        for($count= 1; $count <= ($page_count); $count++) {
                            echo "<option value=\"{$count}\"";
                            if($count == $current_page["position"]){
                                echo " selected" ;
                            }
                            echo ">{$count}</option>";
                       }
                    ?> </select>
            <br>
            <br>
            Visible:&nbsp;
            No <input type="radio" name="visible" value="0" <?php if($current_page["visible"] == 0) {echo " checked";} ?>>&nbsp;
            Yes <input type="radio" name="visible" value="1" <?php if($current_page["visible"] == 1) {echo " checked";} ?>>
            <br> <br>
            Content <br>
            <textarea name="content" style="width:450px;height:200px;"><?php echo htmlentities($current_page["content"]); ?></textarea>
            <br><br>
            <input type="submit" name="submit" value="Submit">
        </form>
    
    </div>
</main>
<?php include("../includes/layouts/footer.php"); ?>