<?php require_once("../includes/session.php");?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_login(); ?>
<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>
<main>
    <nav><br>
        <a href="admin.php">&laquo; Main Menu</a>
    </nav>
    <div id="page">
        <h2>Manage Users</h2>
        <?php echo message(); ?>
        <!-- table for username and edit/delete actions -->
        <table id="list_admin">
            <tr>
                <td><strong>Username</strong></td>
                <td><strong>Actions</strong></td>
            </tr>
            <?php 
                $admin_set = find_all_admin();
                while($admin = mysqli_fetch_assoc($admin_set)) {
                    $output = "<tr>";
                    $output .= "<td>";
                    $output .= htmlentities($admin["username"]);
                    $output .= "</td><td>";
                    $output .= "<a href=\"";
                    $output .= "edit_admin.php?id=";
                    $output .= urlencode($admin["id"]);
                    $output .= "\">Edit</a>&nbsp;&nbsp;&nbsp;<a href=\"";
                    $output .= "delete_admin.php?id=";
                    $output .= $admin["id"];
                    $output .= "\" onclick=\"return confirm('Are you sure?')\"";
                    $output .= ">Delete</a></td></tr>";
                    echo $output; 
                }
            ?>
            
        </table>
        <br>
        <a href="new_admin.php" style="text-decoration:none;font-weight:bold">+ Add New User</a><br>
        
     
    
    </div>
</main>
<?php include("../includes/layouts/footer.php"); ?>
