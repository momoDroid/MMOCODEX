<?php 

            require"../admin-layout/header.php";
            require"../../config/config.php";

            //avoid to bypass using url link
            if(!isset($_SESSION["admin_name"])){
            header("location: ".ADMINURL."/admin-auth/admin.Login.php");
            }


        if(isset($_GET['cat_id'])){
            $cat_id = $_GET['cat_id'];
            //admin query
        $catQuery = $conn->query("SELECT * FROM categories WHERE cat_id = '$cat_id'");

        $catQuery->execute();

        //fetch to display admin
        $displayCat = $catQuery->fetch(PDO::FETCH_OBJ);

        }

        if (isset($_POST['updateCat'])) {
   

            $category = $_POST['category'];
          
        
            // Perform form validation
             if (empty($category)) {
                echo "<script>alert('One or more inputs are empty');</script>";
                    }else
                     {

                    // Prepare and execute the SQL query
                    $updateCat = $conn->prepare("UPDATE categories SET cat_name = :category WHERE cat_id = '$cat_id'");
                        $updateCat->execute([":category" => $category]);

                    // Redirect to category page 
                    header("location: ".ADMINURL."/admin-categories/display.Categories.php");
                    exit();
                } 
                            }
            

?>

<form role="form" action="update.Category.php?cat_id=<?php echo $displayCat->cat_id; ?>" method="POST">
<h1>Update Category</h1>
    <input type="text" name="category" value="<?php echo $displayCat->cat_name;?>">

    <input type="submit" name="updateCat" value="Submit">
</form>