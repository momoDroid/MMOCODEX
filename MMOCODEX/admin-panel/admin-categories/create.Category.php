<?php

        require"../admin-layout/header.php";
        require"../../config/config.php";

    //avoid to bypass using url link
    if(!isset($_SESSION["admin_name"])){
        header("location: ".ADMINURL."/admin-auth/admin.Login.php");
        }

        if (isset($_POST['addCat'])) {
   

            $category = $_POST['category'];
          
        
            // Perform form validation
             if (empty($category)) {
                echo "<script>alert('One or more inputs are empty');</script>";
                    }else
                     {

                    // Prepare and execute the SQL query
                    $catQuery = $conn->prepare("INSERT INTO categories (cat_name) VALUES (:category)");
                        $catQuery->execute([":category" => $category]);

                    // Redirect to category page 
                    header("location: ".ADMINURL."/admin-categories/display.Categories.php");
                    exit();
                } 
                            }

        ?>


<section>
<form role="form" action="create.Category.php" method="POST">
<h1>Add New Category</h1>
    <input type="text" name="category">

    <input type="submit" name="addCat" value="Submit">

                        </form>

                        </section>