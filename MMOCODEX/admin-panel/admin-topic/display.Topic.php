<?php 

        require"../admin-layout/header.php";
        require"../../config/config.php";

        //avoid to bypass using url link
        if(!isset($_SESSION["admin_name"])){
        header("location: ".ADMINURL."/admin-auth/admin.Login.php");
        }
        //admin query
        $topicQuery = $conn->query("SELECT * FROM topic");

        $topicQuery->execute();

        //fetch to display admin
        $fetchtopic = $topicQuery->fetchAll(PDO::FETCH_OBJ);
        ?>
<section>
<table>
<th>#</th>
    <th>Title</th>
    <th>Category</th>
    <th>User</th>
    <th>Date Created</th>
    <th></th>

  <?php foreach($fetchtopic AS $displayTopic):?>
  <tr>
    <td><?php echo $displayTopic->topic_id; ?></td>
    <td><?php echo $displayTopic->title; ?></td>
    <td><?php echo $displayTopic->category; ?></td>
    <td><?php echo $displayTopic->user; ?></td>
    <td><?php echo $displayTopic->created_at; ?></td>
    <td><button><a href="delete.Topic.php?topic_id=<?php echo $displayTopic->topic_id; ?>">DELETE</a></button></td>
  </tr>
  <?php endforeach; ?>
  </table>
  </section>