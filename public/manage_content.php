<?php require_once("../includes/session.php");?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_login(); ?>
<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>
<?php find_selected_page(); ?>
<main>
    <nav>
    <br />
    <a href="admin.php">&laquo; Main Menu</a><br />
      <?php echo navigation($current_subject, $current_page); ?>  
      <br />
      <a href="new_subject.php">+ Add a subject</a>
    </nav>
    <div id="page">
        <?php echo message(); ?>
        <?php if($current_subject) { ?>
            <h2>Manage Subject</h2>
            Menu Name:&nbsp;<?php echo htmlentities($current_subject["menu_name"]); ?>              <br />
            Position: <?php echo $current_subject["position"]; ?> <br />
            Visible: <?php 
                       if ($current_subject["visible"] == 0){ 
                        echo " No"; 
                       } else {
                         echo " Yes";   
                       }
                        ?> <br /><br />
            
            <a href="edit_subject.php?subject=<?php echo urlencode($current_subject["id"])?>">Edit Subject</a><br />
        <hr>
        <h3>Pages in this subject:</h3>
        <ul class="pages">
        <?php $page_set = find_pages_for_subject($current_subject["id"], false); 
        // retrieve pages
            while($page = mysqli_fetch_assoc($page_set)) {
                $output = "<li>";
                $output .= "<a href=\"manage_content.php?page=";
                $output .= urlencode($page["id"]);
                $output .= "\">";
                $output .= htmlentities($page["menu_name"]);
                $output .= "</a></li>";
                echo $output;
            }
                                     
        ?>
        </ul>
        <a href="new_page.php?subject=<?php echo urlencode($current_subject["id"]);?>" style="font-weight:bold;font-size:12px;text-decoration:none">+ Add a new page to this subject</a>
       
        <?php } elseif ($current_page) { ?>
            <h2>Manage Page</h2>
            Page Name:&nbsp;<?php echo htmlentities($current_page["menu_name"]); ?><br>
            Position: <?php echo $current_page["position"]; ?> <br />
            Visible: <?php 
                       if ($current_page["visible"] == 0){ 
                        echo " No"; 
                       } else {
                         echo " Yes";   
                       }
                        ?> <br />
            Content: <br />
            <div class="view-content">
                <?php echo htmlentities($current_page["content"]); ?>
            </div>
        <a href="edit_page.php?page=<?php echo urlencode($current_page["id"]); ?>">Edit Page</a>&nbsp;
        <a href="delete_page.php?page=<?php echo $current_page["id"]; ?>" onclick="return confirm('Are you sure?')">Delete Page</a>
        <?php } else { ?> 
            Please selected a subject or a page.
        <?php } ?>
    </div>
</main>
<?php include("../includes/layouts/footer.php"); ?>
