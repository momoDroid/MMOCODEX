<?php 


            session_start();
            session_unset();
            session_destroy();
            header("location:http://localhost/MMOCODEX_TEST/admin-panel/admin-auth/admin.Login.php");
?>