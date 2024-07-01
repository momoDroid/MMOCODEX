<?php 

if(isset($_POST['submitReply'])){
  $reply = $_POST['reply'];
  $user_id = $_SESSION['user_id'];
  $topic_id = $topic_id;
  $user = $_SESSION['username'];
  $user_img = $_SESSION['user_img'];

  //Insert Reply
  
  if (empty($reply) ) {
      echo "<script>alert('One or more inputs are empty');</script>";
          }else
           {
          // Prepare and execute the SQL query
          $insert = $conn->prepare("INSERT INTO replies (reply , user_id, user_name, user_img, topic_id) VALUES (:reply, :user_id, :user, :user_img, :topic_id)");
              $insert->execute([
                  ":reply" => $reply,
                  ":user_id" => $user_id,
                  ":user" => $user,
                  ":user_img" => $user_img,
                  ":topic_id" => $topic_id
  
              ]);
          // Redirect to category page 
          header("location: ".APPURL."/topic/topic.php?topic_id=".$topic_id."");
          exit();
      } 
}
