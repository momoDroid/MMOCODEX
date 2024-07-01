<?php
require "../includes/header.php";

// If the user is not set, redirect to homepage
if (!isset($_SESSION["username"])) {
    header("location: ".APPURL."");
    exit();
}

// Display the categories
$queryCategory = $conn->query("SELECT * FROM categories");
$allCat = $queryCategory->fetchAll(PDO::FETCH_OBJ);

// If the form is submitted
if (isset($_POST['create'])) {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $body = $_POST['body'];
    $user = $_SESSION['username'];
    $user_img = $_SESSION['user_img'];
    $topic_img = '';

    // Perform form validation
    if (empty($title) || empty($category) || empty($body)) {
        echo "One or more inputs are empty";
    } else {
        // Handle the file upload
    if (isset($_FILES['topic_img']) && $_FILES['reply_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "topic.Upload/";
        $file_name = basename($_FILES["topic_img"]["name"]);
        $target_file = $target_dir . $file_name;

        // Validate the file as an image
        if (getimagesize($_FILES["topic_img"]["tmp_name"]) !== false) {
            if (move_uploaded_file($_FILES["topic_img"]["tmp_name"], $target_file)) {
                $topic_img = $file_name; // Store only the file name
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            }
        } else {
            echo "<script>alert('File is not an image.');</script>";
        }
    }

        // Prepare and execute the SQL query
        $insert = $conn->prepare("INSERT INTO topic (title, category, body, user, user_img, topic_img) VALUES (:title, :category, :body, :user, :user_img, :topic_img)");
        $insert->execute([
            ":title" => $title,
            ":category" => $category,
            ":body" => $body,
            ":user" => $user,
            ":user_img" => $user_img,
            ":topic_img" => $topic_img
        ]);

        // Redirect to category page
        header("location: ".APPURL."");
        exit();
    }
}
?>

<section class="create-post-section">
    <h1>Create Post</h1>
    <form action="create.Topic.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="post-title">Title</label>
            <input type="text" id="post-title" name="title" placeholder="Enter your post title" required>
        </div>
        <div class="form-group">
            <label for="game-list">Select Game</label>
            <select id="game-list" name="category" required>
                <option disabled selected value="">Select Game</option>
                <?php foreach($allCat as $displayCat): ?>
                    <option value="<?php echo $displayCat->cat_name;?>"><?php echo $displayCat->cat_name;?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="post-content">Content</label>
            <textarea id="post-content" name="body" rows="10" placeholder="Enter your post content" required></textarea>
        </div>
        <div class="form-group">
            <label for="file-upload" class="file-upload-label">
                <img src="<?php echo APPURL;?>/assets/icons/image.png" alt="Attach File" class="attach-file-icon">
                Attach File
            </label>
            <input type="file" id="file-upload" name="topic_img" class="file-upload-input">
        </div>
        <div class="form-group">
            <button type="submit" name="create" class="submit-button">Submit Post</button>
        </div>
    </form>
</section>
