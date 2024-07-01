<?php 


            session_start();
            session_unset();
            session_destroy();
            header("location:http://localhost/MMOCODEX/admin-panel/admin-auth/admin.Login.php");
?>
