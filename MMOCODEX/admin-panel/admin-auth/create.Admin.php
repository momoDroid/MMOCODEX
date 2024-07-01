<?php

require"../admin-layout/header.php";
require"../../config/config.php";



    //avoid to bypass using url link
if(!isset($_SESSION["admin_name"])){
    header("location: ".ADMINURL."/admin-auth/admin.Login.php");
    }
        
//IF CLICK SUBMIT
  

if (isset($_POST['createAdmin'])) {
    $name = $_POST['adminName'];
    $email = $_POST['adminEmail'];
    $password = $_POST['adminPassword'];

    // Perform form validation
    if (empty($name) || empty($email) || empty($password)) {
        echo "<script>alert('One or more inputs are empty');</script>";
    } else {

        // Hash the password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the SQL query
        $insertAdmin = $conn->prepare("INSERT INTO admins (admin_name, admin_email, admin_password) VALUES (:name, :email, :password)");
        $insertAdmin->execute([
            ":name" => $name,
            ":email" => $email,
            ":password" => $passwordHash
        ]);

}

            // Redirect to login page after successful registration
            header("location: ".ADMINURL."/admin-auth/display.Admin.php");
            exit();
        }
        

?>

<h2>Create New Admin</h2>
<form action="create.Admin.php" method="post" enctype="multipart/form-data">
    <label for="name">Name:</label>

    <input type="text" id="name" name="adminName" required><br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="adminEmail" required><br><br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="adminPassword" required><br><br>

    <input type="submit" name="createAdmin" value="Create Admin">
</form>

<?php require"../admin-layout/footer.php"; ?>

