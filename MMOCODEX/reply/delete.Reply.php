<?php 

require "../includes/header.php";



// Redirect if the user is not the owner of the topic


if (isset($_GET['rep_id'])) {
    $rep_id = $_GET['rep_id'];

    $selectReply = $conn->prepare("SELECT * FROM replies WHERE rep_id = '$rep_id'");
    $selectReply->execute();
    $reply = $selectReply->fetch(PDO::FETCH_OBJ);

    //avoid accessing delete function
if ($reply->user_id !== $_SESSION['user_id']) {
    header("location: " . APPURL);
    
}else{
         // Use prepared statement to avoid SQL injection
    $deleteReply = $conn->prepare("DELETE FROM replies WHERE rep_id = :rep_id");
    $deleteReply->execute([':rep_id' => $rep_id]);

    // Redirect to index page after deleting the topic
    header("location:../topic/topic.php?topic_id=" . $reply->topic_id);
   
    }

}


?>