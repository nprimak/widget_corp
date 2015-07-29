<?php 
    if(!isset($layout_context)) {
        $layout_context = "public";
        
    } 
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Widget Corp <?php if($layout_context == "admin") {echo "Admin"; } ?></title>
        <link href="css/public.css" media="all" rel="stylesheet" type="text/css" />
	</head>
	<body>
        <header><h1>Widget Corp <?php if($layout_context == "admin") {echo "Admin"; } ?></h1></header>