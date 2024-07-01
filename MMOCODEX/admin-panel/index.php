<?php 

    require "admin-layout/header.php";
    require"../config/config.php";

if(!isset($_SESSION["admin_name"])){
    header("location: ".ADMINURL."admin-auth/admin.Login.php");
    }


    //count Topics
    $topicQuery = $conn->query("SELECT COUNT(*) AS count_topic FROM topic");
    $topicQuery->execute();
    $countTopic = $topicQuery->fetch(PDO::FETCH_OBJ);


     //count Categories
     $catQuery = $conn->query("SELECT COUNT(*) AS count_categories FROM categories");
     $catQuery->execute();
     $countCat = $catQuery->fetch(PDO::FETCH_OBJ);

     //count Replies
     $repQuery = $conn->query("SELECT COUNT(*) AS count_rep FROM replies");
     $repQuery->execute();
     $countRep = $repQuery->fetch(PDO::FETCH_OBJ);

     //count Users
     $userQuery = $conn->query("SELECT COUNT(*) AS count_user FROM user");
     $userQuery->execute();
     $countUSer = $userQuery->fetch(PDO::FETCH_OBJ);

     //count Admin
     $adminQuery = $conn->query("SELECT COUNT(*) AS count_admin FROM admins");
     $adminQuery->execute();
     $countAdmin = $adminQuery->fetch(PDO::FETCH_OBJ);

    ?>


<section>
<div class="thumbnail">
  <h3>Topics</h3>
  <p>Number of Topics: <span><?php echo $countTopic->count_topic; ?></span></p>
</div>
<div class="thumbnail">
  <h3>Categories</h3>
  <p>Number of Categories: <span><?php echo $countCat->count_categories; ?></span></p>
    </div>
  <div class="thumbnail">
  <h3>Replies</h3>
  <p>Number of Replies: <span><?php echo $countRep->count_rep; ?></span></p>
    </div>
  <div class="thumbnail">
  <h3>Users</h3>
  <p>Number of Users: <span><?php echo $countUSer->count_user; ?></span></p>
    </div>
  <div class="thumbnail">
  <h3>Admins</h3>
  <p>Number of Admins: <span><?php echo $countAdmin->count_admin; ?></span></p>
    </div>
</div>
</section>



<?php require "admin-layout/footer.php"; ?>
    
