<?php

session_start();

// Define APPURL constant
define("APPURL", "http://localhost/MMOCODEX");

include_once $_SERVER['DOCUMENT_ROOT'] . "/MMOCODEX/config/config.php";
       
//Display Categories
$queryCategory = $conn->query("SELECT * FROM categories");
$queryCategory->execute();

$allCat = $queryCategory->fetchAll(PDO::FETCH_OBJ);
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MMCODEX</title>
    <link rel="stylesheet" href="<?php echo APPURL;?>/assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-left">
                <div class="logo">
                    <img src="<?php echo APPURL;?>/assets/icons/MOMOCODEX-CUT-removebg-preview.png" alt="">
                </div>
                <ul class="nav-links">
                    <li><a href="<?php echo APPURL;?>">Home</a></li>
                    <li class="dropdown">
                        <a href="#">Categories</a>
                        <div class="dropdown-content">
                        <?php foreach($allCat as $displayCat): ?>
                            <a href="<?php echo APPURL; ?>/categories/show.Categories.php?cat_name=<?php echo $displayCat->cat_name; ?>"><?php echo $displayCat->cat_name ;?></a>
                            <?php endforeach; ?>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="nav-center">
                <div class="search-bar-container">
                <form action="<?php echo APPURL; ?>/topic/search.Topic.php" method="GET">
                    <input type="text" name="searchTerm" placeholder="Search..." class="search-bar">
                    <div class="search-bar-icon"><button type="submit" style=" background-color: transparent; border: none; padding: 0;"><img src="<?php echo APPURL;?>/assets/icons/search_glass.png" alt="Search"></button></div>
                    </form>
                </div>
            </div>
            <div class="nav-right">
               
                <?php if(isset($_SESSION["username"])):  ?>
                <a href="<?php echo APPURL;?>/topic/create.Topic.php" class="create-post">Create Post</a>
                
                <div class="user-dropdown">
                    <img src="<?php echo APPURL;?>/img/<?php echo $_SESSION['user_img'];?>" alt="User" class="user-logo">
                    <div class="user-dropdown-content">
                        <a href="<?php echo APPURL;?>/users/profile.php?profile_id=<?php echo $_SESSION['username']; ?>">View Profile</a>
                        <a href="<?php echo APPURL; ?>/users/update.User.php?user_id=<?php echo $_SESSION['user_id']; ?>">Edit Profile</a>
                        <a href="<?php echo APPURL;?>/auth/logout.php">Logout</a>
                    </div>
                </div>
                <a href="" class="username"><?php echo $_SESSION['username'];?></a>
                    <?php else :?>
                    <a href="<?php echo APPURL;?>/auth/login.php" class="login">Login</a>
                     <a href="<?php echo APPURL;?>/auth/register.php" class="sign-up">Sign Up</a>
                <?php endif; ?> 
                
                
                </div>
            </div>
        </div>
    </nav>

    
        