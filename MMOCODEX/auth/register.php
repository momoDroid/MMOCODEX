<?php 

require"../config/config.php";

//if the user try to bypass using url link
if(isset($_SESSION["username"])){
    header("location: ".APPURL."");
    }
        
//IF CLICK SUBMIT
  

if (isset($_POST['submit'])) {
   

    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $about = $_POST['about'];
    $avatar = $_FILES['avatar']['name'];
    $avatar_tmp = $_FILES['avatar']['tmp_name'];

    // Perform form validation
    if (empty($name) || empty($username) || empty($email) || empty($password) || empty($about)) {
        echo "<script>alert('One or more inputs are empty');</script>";
    } else {
        // Hash the password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Move uploaded file to desired directory
        $dir = "../img/" . basename($avatar);
        if (move_uploaded_file($avatar_tmp, $dir)) {
            // Prepare and execute the SQL query
            $insert = $conn->prepare("INSERT INTO user (name, username, email, password, about, avatar) VALUES (:name, :username, :email, :password, :about, :avatar )");
            $insert->execute([
                ":name" => $name,
                ":username" => $username,
                ":email" => $email,
                ":password" => $passwordHash,
                ":about" => $about,
                ":avatar" => $avatar,
            ]);

            // Redirect to login page after successful registration
            header("location: login.php");
            exit();
        } else {
            echo "<script>alert('File upload failed');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
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

/* Registration Container Styles */
.registration-container {
    background-color:#18181a;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
    padding: 40px;
    width: 400px;
    max-width: 100%;
}

/* Form Styles */
.registration-form {
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

input, textarea {
    background: #1b1d2a;
    border: 1px solid #444;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 15px;
    color: #fff;
    font-size: 16px;
    width: 100%;
}

input[type="file"] {
    padding: 10px;
    border: none;
}

.file-label {
    background-color: #444;
    color: #fff;
    padding: 10px;
    border-radius: 5px;
    text-align: center;
    cursor: pointer;
    margin-bottom: 15px;
    width: 100%;
}

button {
    background-color:#323339;
    border: none;
    border-radius: 5px;
    color: white;
    padding: 10px;
    font-size: 16px;
    cursor: pointer;
    width: 100%;
}

button:hover {
    background-color: #4473c4;
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
.upload-button {
    display: inline-block;
    background-color: #444;
    color: #fff;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    text-align: center;
    margin-bottom: 15px;
    width: 100%;
}

.upload-button:hover {
    background-color: #555;
}

    </style>
</head>
<body>
    <div class="registration-container">
        <form class="registration-form" action="register.php" method="post" enctype="multipart/form-data">
            <img src="../assets/icons/MOMOCODEX-CUT-removebg-preview.png" alt="Website Logo" class="website-logo">
            <h2>Register</h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <textarea name="about" placeholder="about" rows="4" required></textarea>
            <input type="file" id="user-logo" name="avatar" accept="image/*" required hidden>
            <label for="user-logo" class="upload-button">Upload Profile</label>
            <button type="submit" name="submit">Register</button>
            <p class="message">Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>
</body>
</html>
