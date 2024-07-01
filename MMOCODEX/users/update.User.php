<?php
require "../includes/header.php";

// Check if the user is logged in, otherwise redirect to the homepage
if (!isset($_SESSION["username"])) {
    header("location: " . APPURL);
    exit();
}

// Fetch user data
$user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT);

if (!$user_id) {
    echo "Profile ID is not set";
    header("location: " . APPURL);
    exit();
}

$userQuery = $conn->prepare("SELECT * FROM user WHERE user_id = :user_id");
$userQuery->execute([':user_id' => $user_id]);
$displayUser = $userQuery->fetch(PDO::FETCH_OBJ);

if (!$displayUser) {
    echo "User not found";
    header("location: " . APPURL);
    exit();
}

// If form is submitted for update
if (isset($_POST['update'])) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $about = filter_input(INPUT_POST, 'about', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $avatar = $displayUser->avatar; // Default to current avatar

    // Handle file upload for avatar
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "../img/";
        $file_name = uniqid() . '-' . basename($_FILES["avatar"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is an image
        $check = getimagesize($_FILES["avatar"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
                $avatar = $file_name; // Store only the file name
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "File is not an image.";
        }
    }

    // Perform form validation
    if (empty($name) || empty($username) || empty($email) || empty($about)) {
        echo "One or more inputs are empty";
    } else {
        try {
            // Begin transaction
            $conn->beginTransaction();

            // If password is not empty, hash it
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $updateUser = $conn->prepare("UPDATE user SET name = :name, username = :username, email = :email, about = :about, password = :password, avatar = :avatar WHERE user_id = :user_id");
                $updateUser->execute([
                    ":name" => $name,
                    ":username" => $username,
                    ":email" => $email,
                    ":about" => $about,
                    ":password" => $hashedPassword,
                    ":avatar" => $avatar,
                    ":user_id" => $user_id
                ]);
            } else {
                // Prepare and execute the SQL query without password
                $updateUser = $conn->prepare("UPDATE user SET name = :name, username = :username, email = :email, about = :about, avatar = :avatar WHERE user_id = :user_id");
                $updateUser->execute([
                    ":name" => $name,
                    ":username" => $username,
                    ":email" => $email,
                    ":about" => $about,
                    ":avatar" => $avatar,
                    ":user_id" => $user_id
                ]);
            }

            // Update avatar in posts and reply 
            $updatePosts = $conn->prepare("UPDATE topic SET user_img = :avatar WHERE user = :username");
            $updatePosts->execute([
                ":avatar" => $avatar,
                ":username" => $username
            ]);
            $updateReply = $conn->prepare("UPDATE replies SET user_img = :avatar WHERE user_name = :username");
            $updateReply->execute([
                ":avatar" => $avatar,
                ":username" => $username
            ]);

            // Commit transaction
            $conn->commit();

            // Update session variables
            $_SESSION['username'] = $username; // Update username
            $_SESSION['user_img'] = $avatar; // Update user image

            // Redirect to profile page
            header("location: " . APPURL . "/users/profile.php?profile_id=" . $username);
            exit();
        } catch (PDOException $e) {
            // Rollback transaction on error
            $conn->rollBack();
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

<section class="create-post-section">
    <h1>Update Profile</h1>

    <form role="form" action="update.User.php?user_id=<?php echo $displayUser->user_id; ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="post-title">Name</label>
            <input type="text" id="post-title" name="name" value="<?php echo htmlspecialchars($displayUser->name ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="post-title">Username</label>
            <input type="text" id="post-title" name="username" value="<?php echo htmlspecialchars($displayUser->username ?? ''); ?>">
        </div>
        <div class="form-group">
            <label class="post-title">Email</label><br>
            <input type="email" id="post-title" name="email" value="<?php echo htmlspecialchars($displayUser->email ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="post-title">Password</label><br>
            <input type="password" id="post-title" name="password">
        </div>
        <div class="form-group">
            <label for="post-content">About</label><br>
            <textarea name="about" id="post-content" cols="30" rows="10"><?php echo htmlspecialchars($displayUser->about ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label class="post-title">Profile Picture</label><br>
            <img src="../img/<?php echo htmlspecialchars($displayUser->avatar ?? ''); ?>" style="width:300px; height:300px;" class="post-image" alt="Profile Picture"><br>
            <input type="file" name="avatar">
        </div>
        <input type="submit" class="submit-button" name="update" value="Update"><br>
    </form>
</section>
