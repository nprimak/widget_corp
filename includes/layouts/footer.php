<footer>Copyright <?php  date_default_timezone_set('EST'); echo date('Y') ;?>, Widget Corp</footer>   
	</body>
</html>
<?php 
    // 5. Close database connection
if (isset($connection)){    
    mysqli_close($connection);
}
?>