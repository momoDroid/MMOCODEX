<?php
  require"../../config/config.php";
  require"../admin-layout/header.php";

  if(!isset($_SESSION["admin_name"])){
      header("location: ".ADMINURL."/admin-auth/admin.Login.php");
      }

  if (isset($_GET['topic_id'])) {
      $topic_id = $_GET['topic_id'];


      $deleteTopic = $conn->prepare("DELETE FROM topic WHERE topic_id = :topic_id");
      $deleteTopic->execute([':topic_id' => $topic_id]);

      // Redirect to index page after deleting the topic
      header("location:display.Topic.php");
      exit; // Stop execution after redirect
  }
  
?>