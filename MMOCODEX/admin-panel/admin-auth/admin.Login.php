<?php 


        require"../admin-layout/header.php";
        require"../../config/config.php";

//if the user try to bypass using url link
// if(isset($_SESSION["username"])){
//     header("location: ".ADMINURL."");
//     }
//IF CLICK SUBMIT
  

if (isset($_POST['submit'])) {
   
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Perform form validation
    if (empty($email) || empty($password)) {
        echo "<script>alert('One or more inputs are empty');</script>";
    } else {
         

       //write the queries

       $login = $conn->query("SELECT * FROM admins WHERE admin_email OR admin_name ='$email'");
       $login->execute();

       $fetch =$login->fetch(PDO::FETCH_ASSOC);
       
       if($login->rowCount() > 0){

           if(password_verify($password, $fetch['admin_password'])){
            $_SESSION['admin_name'] = $fetch['admin_name'];
            $_SESSION['admin_email'] = $fetch['admin_email'];
          
            
             header("location: ".ADMINURL."");
           }else{
            echo "<script>alert('email or password is wrong');</script>";
           }
       }else{
        echo "<script>alert('email or password is wrong');</script>";
       }
    }
}
?>

<section>
<h2>Sign In</h2>
<form action="admin.Login.php" method="post" enctype="multipart/form-data">
    <label for="name">Email:</label>
    <input type="name" id="name" name="email" required><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" name="submit" value="Login">
</form>
</section>