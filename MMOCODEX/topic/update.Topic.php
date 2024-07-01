<?php 
require "../includes/header.php";

// Display the categories
$queryCategory = $conn->query("SELECT * FROM categories");
$queryCategory->execute();

$allCat = $queryCategory->fetchAll(PDO::FETCH_OBJ);

// Check if the user is logged in, otherwise redirect to the homepage
if (!isset($_SESSION["username"])) {
    header("location: " . APPURL);
    exit();
}

// Initialize variables
$topic_id = $_GET['topic_id'] ?? null;
$topic = null;

// Fetch topic data
if ($topic_id) {
    $selectTopic = $conn->prepare("SELECT * FROM topic WHERE topic_id = :topic_id");
    $selectTopic->execute([':topic_id' => $topic_id]);
    $topic = $selectTopic->fetch(PDO::FETCH_OBJ);
}

if ($topic && $topic->user !== $_SESSION['username']) {
    header("location:" . APPURL . "/404.php");
    exit();
}

// If form is submitted for update
if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $body = $_POST['body'];
    $user = $_SESSION['username'];
    $fileName = $topic->topic_img; // Default to current image

    // Check if a new file is uploaded
    if (isset($_FILES['file-upload']) && $_FILES['file-upload']['error'] == 0) {
        $targetDir = "topic.Upload/";
        $fileName = basename($_FILES["file-upload"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $allowedTypes)) {
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["file-upload"]["tmp_name"], $targetFilePath)) {
                // Optionally, delete the old file if a new file is successfully uploaded
                if (!empty($topic->topic_img) && file_exists("topic.Upload/" . $topic->topic_img)) {
                    unlink("topic.Upload/" . $topic->topic_img);
                }
            } else {
                echo "<script>alert('Failed to upload the image');</script>";
            }
        } else {
            echo "<script>alert('Only JPG, JPEG, PNG, and GIF files are allowed');</script>";
        }
    }

    // Perform form validation
    if (empty($title) || empty($category) || empty($body)) {
        echo "<script>alert('One or more inputs are empty');</script>";
    } else {
        // Prepare and execute the SQL query
        $updateTopic = $conn->prepare("UPDATE topic SET title = :title, category = :category, body = :body, user = :user, topic_img = :topic_img WHERE topic_id = :topic_id");
        $updateTopic->execute([
            ":title" => $title,
            ":category" => $category,
            ":body" => $body,
            ":user" => $user,
            ":topic_img" => $fileName,
            ":topic_id" => $topic_id
        ]);

        // Redirect to category page
        header("location:" . APPURL . "/topic/topic.php?topic_id=" . $topic->topic_id);
        exit();
    }
}
?>

<section class="create-post-section">
    <h1>Edit Post</h1>
    <form action="#" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="post-title">Title</label>
            <input type="text" id="post-title" name="title" value="<?php echo htmlspecialchars($topic->title ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="game-list">Select Game</label>
            <select id="game-list" name="category" required>
                <option disabled selected value="">Select Game</option>
                <?php foreach($allCat as $displayCat): ?>
                    <option value="<?php echo htmlspecialchars($displayCat->cat_name); ?>" <?php if ($topic && $displayCat->cat_name == $topic->category) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($displayCat->cat_name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="post-content">Content</label>
            <textarea id="post-content" name="body" rows="10" required><?php echo htmlspecialchars($topic->body ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label for="file-upload" class="file-upload-label">
                <img src="../assets/icons/image.png" alt="Attach File" class="attach-file-icon">
                Attach File
            </label>
            <input type="file" id="file-upload" name="file-upload" class="file-upload-input">
        </div>
        <div class="form-group">
            <button type="submit" name="update" class="submit-button">Submit Post</button>
        </div>
    </form>
</section>
