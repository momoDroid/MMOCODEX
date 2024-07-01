<?php 

require "../includes/header.php";

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("location: " . APPURL);
    exit();
}

// Initialize variables
if (isset($_GET['rep_id'])) {
    $rep_id = $_GET['rep_id'];

    // Fetch reply
    $selectReply = $conn->prepare("SELECT * FROM replies WHERE rep_id = :rep_id");
    $selectReply->execute([":rep_id" => $rep_id]);
    $displayReply = $selectReply->fetch(PDO::FETCH_OBJ);

    // Check if the reply belongs to the logged-in user
    if ($displayReply->user_id !== $_SESSION['user_id']) {
        header("location: " . APPURL);
        exit();
    }

    // If form is submitted for update
    if (isset($_POST['updateReply'])) {
        $reply = $_POST['reply'];
        $fileName = $displayReply->reply_img; // Default to current image

        // Handle file upload
        if (isset($_FILES['file-upload']) && $_FILES['file-upload']['error'] == 0) {
            $targetDir = "../topic/reply.Upload/";
            $fileName = basename($_FILES["file-upload"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            // Allow certain file formats
            $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'pdf');
            if (in_array($fileType, $allowTypes)) {
                // Upload file to server
                if (move_uploaded_file($_FILES["file-upload"]["tmp_name"], $targetFilePath)) {
                    // Optionally, delete the old file if a new file is successfully uploaded
                    if (!empty($displayReply->reply_img) && file_exists($targetDir . $displayReply->reply_img)) {
                        unlink($targetDir . $displayReply->reply_img);
                    }
                } else {
                    echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
                }
            } else {
                echo "<script>alert('Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed.');</script>";
            }
        }

        // Perform form validation
        if (empty($reply)) {
            $error_message = "Reply cannot be empty.";
        } else {
            // Prepare and execute the SQL query
            $updateReply = $conn->prepare("UPDATE replies SET reply = :reply, reply_img = :reply_img WHERE rep_id = :rep_id");
            $updateReply->execute([
                ":reply" => $reply,
                ":reply_img" => $fileName,
                ":rep_id" => $rep_id
            ]);

            // Redirect to topic page
            header("location:" . APPURL . "/topic/topic.php?topic_id=" . $displayReply->topic_id);
            exit();
        }
    }
}
?>
<!-- If input field is missing or error -->
<?php if (isset($error_message)): ?>
    <p><?php echo $error_message; ?></p>
<?php endif; ?>
<!-- Update Reply Form -->
<section class="create-post-section">
    <h1>Update Reply</h1>
    <form action="update.Reply.php?rep_id=<?php echo htmlspecialchars($rep_id); ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="post-content">Content</label>
            <textarea id="post-content" name="reply" rows="10" placeholder="Enter your post content" required><?php echo htmlspecialchars($displayReply->reply ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label for="file-upload" class="file-upload-label">
                <img src="<?php echo APPURL;?>/assets/icons/image.png" alt="Attach File" class="attach-file-icon">
                Attach File
            </label>
            <input type="file" id="file-upload" name="file-upload" class="file-upload-input">
        </div>
        <div class="form-group">
            <button type="submit" name="updateReply" class="submit-button">Update Reply</button>
        </div>
    </form>
</section>
