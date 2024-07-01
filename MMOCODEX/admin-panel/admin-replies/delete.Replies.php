<?php

        require"../../config/config.php";
        require"../admin-layout/header.php";

        if(!isset($_SESSION["admin_name"])){
            header("location: ".ADMINURL."/admin-auth/admin.Login.php");
            }

        if (isset($_GET['rep_id'])) {
            $rep_id = $_GET['rep_id'];


            $deleteReply = $conn->prepare("DELETE FROM replies WHERE rep_id = :rep_id");
            $deleteReply->execute([':rep_id' => $rep_id]);

            // Redirect to index page after deleting the topic
            header("location:display.Replies.php");
            exit; // Stop execution after redirect
        }
        

?>