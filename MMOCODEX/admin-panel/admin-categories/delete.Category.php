<?php

        require"../../config/config.php";
        require"../admin-layout/header.php";

        if(!isset($_SESSION["admin_name"])){
            header("location: ".ADMINURL."/admin-auth/admin.Login.php");
            }

        if (isset($_GET['cat_id'])) {
            $cat_id = $_GET['cat_id'];


            $deleteTopic = $conn->prepare("DELETE FROM categories WHERE cat_id = :cat_id");
            $deleteTopic->execute([':cat_id' => $cat_id]);

            // Redirect to index page after deleting the topic
            header("location:display.Categories.php");
            exit; // Stop execution after redirect
        }
        

?>