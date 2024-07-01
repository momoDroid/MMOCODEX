<?php 


        require"../admin-layout/header.php";
        require"../../config/config.php";

    //avoid to bypass using url link
    if(!isset($_SESSION["admin_name"])){
      header("location: ".ADMINURL."/admin-auth/admin.Login.php");
      }
        //admin query
        $adminQuery = $conn->query("SELECT * FROM admins");

        $adminQuery->execute();

        //fetch to display admin
        $fetchAdmin = $adminQuery->fetchAll(PDO::FETCH_OBJ);
        ?>

<section>
    <h1><button><a href="<?php echo ADMINURL;?>admin-auth/create.Admin.php">Create Admins</a></button></h1>
<table>
  <tr>
    <th>#</th>
    <th>Username</th>
    <th>Email</th>
    <th>Date Created</th>
    <th></th>
  </tr>
  <?php foreach($fetchAdmin AS $displayAdmin):?>
  <tr>
    <td><?php echo $displayAdmin->admin_id; ?></td>
    <td><?php echo $displayAdmin->admin_name; ?></td>
    <td><?php echo $displayAdmin->admin_email; ?></td>
    <td><?php echo $displayAdmin->created_at; ?></td>
    <td><button><a href="admin.Delete.php?admin_id=<?php echo $displayAdmin->admin_id; ?>">Delete</a></button></td>
  </tr>
  <?php endforeach; ?>
</table>
</section>