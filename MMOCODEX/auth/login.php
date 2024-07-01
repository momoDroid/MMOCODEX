<?php 
// Start session
session_start();

// Define APPURL constant
define("APPURL", "http://localhost/MMOCODEX");
require "../config/config.php";
       
// Redirect if the user is already logged in
if(isset($_SESSION["username"])){
    header("location: " . APPURL);
    exit; // Exit to prevent further execution
}

// IF CLICK SUBMIT
if (isset($_POST['submit'])) {
   
    // Get input data
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Perform form validation
    if (empty($email) || empty($password)) {
        echo "<script>alert('One or more inputs are empty');</script>";
    } else {
       // Prepare and execute SQL query
       $login = $conn->prepare("SELECT * FROM user WHERE email = ? OR username = ?");
       $login->execute([$email, $email]);

       // Fetch user data
       $fetch = $login->fetch(PDO::FETCH_ASSOC);
       
       // Check if user exists
       if($login->rowCount() > 0){
           // Verify password
           if(password_verify($password, $fetch['password'])){
                // Store user data in session
                $_SESSION['username'] = $fetch['username'];
                $_SESSION['user_id'] = $fetch['user_id'];
                $_SESSION['email'] = $fetch['email'];
                $_SESSION['name'] = $fetch['name'];
                $_SESSION['user_img'] = $fetch['avatar'];
                
                // Redirect to index page
                header("location: ../index.php");
                exit; // Exit after redirection
           } else {
                echo "<script>alert('Email or password is wrong');</script>";
           }
       } else {
            echo "<script>alert('Email or password is wrong');</script>";
       }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        /* Basic CSS Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body Styles */
body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #1b1d2a;
    font-family: Arial, sans-serif;
    color: #fff;
}

/* Login Container Styles */
.login-container {
    background-color: #0d0e1d;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
    padding: 40px;
    width: 400px;
    max-width: 100%;
}

/* Form Styles */
.login-form {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.website-logo {
    width: 250px;
    height: auto;
    margin-bottom: 20px;
}

h2 {
    margin-bottom: 20px;
    text-align: center;
}

input {
    background: #1b1d2a;
    border: 1px solid #444;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 15px;
    color: #fff;
    font-size: 16px;
    width: 100%;
}

button {
    background-color: #323339;
    border: none;
    border-radius: 5px;
    color: white;
    padding: 10px;
    font-size: 16px;
    cursor: pointer;
    width: 100%;
}

button:hover {
    background-color:#4473c4;
}

/* Message Styles */
.message {
    margin-top: 15px;
    font-size: 14px;
    text-align: center;
}

.message a {
    color: #4473c4;
    text-decoration: none;
}

.message a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
    <div class="login-container">
        <form class="login-form" action="login.php" method="post" enctype="multipart/form-data">
        <img src="<?php echo APPURL;?>/assets/icons/MOMOCODEX-CUT-removebg-preview.png" alt="">
            <h2>Login</h2>
            <input type="text" name="email" placeholder="Username or Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="submit">Login</button>
            <p class="message">Don't have an account? <a href="register.php">Sign Up</a></p>
        </form>
    </div>
</body>
</html>