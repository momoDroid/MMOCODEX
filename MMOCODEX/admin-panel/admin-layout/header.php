<?php 

session_start();
define("ADMINURL", "http://localhost/MMOCODEX/admin-panel/");


?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?php echo ADMINURL;?>/assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

	<title></title>
</head>
<body>
<header>
  <i class="fas fa-cogs"></i> Admin Dashboard
</header>
<nav>
  
    <ul><?php if(isset($_SESSION["admin_name"])):  ?>
        <li><h2 class="username"><?php echo $_SESSION['admin_name']; ?></h2></li>
        <?php else :?>
          <?php endif; ?> 
        <li><a href="<?php echo ADMINURL;?>admin-auth/display.Admin.php">Admins</a></li>
        <li><a href="<?php echo ADMINURL;?>admin-categories/display.Categories.php">Categories</a></li>
        <li><a href="<?php echo ADMINURL;?>admin-topic/display.Topic.php">Topics</a></li>
        <li><a href="<?php echo ADMINURL;?>admin-replies/display.Replies.php">Replies</a></li>
        <li><a href="<?php echo ADMINURL;?>admin-auth/admin.Logout.php">Logout</a></li>

       
    </ul>
</nav>
