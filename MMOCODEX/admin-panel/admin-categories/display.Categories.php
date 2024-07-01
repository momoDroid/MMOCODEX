<?php

require"../admin-layout/header.php";
        require"../../config/config.php";

    //avoid to bypass using url link
    if(!isset($_SESSION["admin_name"])){
      header("location: ".ADMINURL."/admin-auth/admin.Login.php");
      }
        //admin query
        $catQuery = $conn->query("SELECT * FROM categories");

        $catQuery->execute();

        //fetch to display admin
        $fetchCat = $catQuery->fetchAll(PDO::FETCH_OBJ);
        ?>

<section>
    <h1><button><a href="create.Category.php">Add Categories</a></button></h1>
  
<table>
  <tr>
    <th>#</th>
    <th>Categories</th>
    <th>Date Created</th>
    <th></th>
    <th></th>
  </tr>
  <?php foreach($fetchCat AS $displayCat):?>
  <tr>
    <td><?php echo $displayCat->cat_id; ?></td>
    <td><?php echo $displayCat->cat_name; ?></td>
    <td><?php echo $displayCat->created_at; ?></td>
    <td><button><a href="update.Category.php?cat_id=<?php echo $displayCat->cat_id; ?>">Update</a></button></td>
    <td><button><a href="delete.Category.php?cat_id=<?php echo $displayCat->cat_id; ?>">Delete</a></button></td>

  </tr>
  <?php endforeach; ?>
</table>
</section>