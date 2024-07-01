<?php 

require "../includes/header.php";





if (isset($_GET['topic_id'])) {
    $topic_id = $_GET['topic_id'];

    // Use prepared statement to select the topic based on topic_id
    $selectTopic = $conn->prepare("SELECT * FROM topic WHERE topic_id = :topic_id");
    $selectTopic->execute([':topic_id' => $topic_id]);
    $topic = $selectTopic->fetch(PDO::FETCH_OBJ);

    // Check if the topic exists
    if ($topic) {
        // Check if the user is authorized to delete the topic
        if ($topic->user !== $_SESSION['username']) {
            header("location: " . APPURL);
            exit; // Stop execution to prevent further processing
        } else {
            // Use prepared statement to avoid SQL injection
            $deleteTopic = $conn->prepare("DELETE FROM topic WHERE topic_id = :topic_id");
            $deleteTopic->execute([':topic_id' => $topic_id]);

            // Redirect to index page after deleting the topic
            header("location: " . APPURL);
            exit; // Stop execution after redirect
        }
    } else {
        // Topic not found, handle accordingly (e.g., display an error message)
        header("location:".APPURL."/404.php");
    }
}


?>