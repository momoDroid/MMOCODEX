<?php

        require"../../config/config.php";
        require"../admin-layout/header.php";

        if(!isset($_SESSION["admin_name"])){
            header("location: ".ADMINURL."/admin-auth/admin.Login.php");
            }

        if (isset($_GET['admin_id'])) {
            $admin_id = $_GET['admin_id'];


            $deleteAdmin = $conn->prepare("DELETE FROM admins WHERE admin_id = :admin_id");
            $deleteAdmin->execute([':admin_id' => $admin_id]);

            // Redirect to index page after deleting the topic
            header("location:display.Admin.php");
            exit; // Stop execution after redirect
        }
        

?>