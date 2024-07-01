<?php 

require "../includes/header.php";

// Redirect to 404 page if topic_id is not set
if (!isset($_GET['topic_id'])) {
    header("location:".APPURL."/404.php");
    exit;
}

$topic_id = $_GET['topic_id'];

// Fetch the topic
$topicQuery = $conn->prepare("SELECT * FROM topic WHERE topic_id = :topic_id");
$topicQuery->bindParam(':topic_id', $topic_id);
$topicQuery->execute();
$singleTopic = $topicQuery->fetch(PDO::FETCH_OBJ);

// Redirect to 404 page if the topic doesn't exist
if (!$singleTopic) {
    header("location:".APPURL."/404.php");
    exit;
}

// Fetch number of posts by the user
$topicCountQuery = $conn->prepare("SELECT COUNT(*) AS count_topic FROM topic WHERE user = :user");
$topicCountQuery->execute([':user' => $singleTopic->user]);
$count = $topicCountQuery->fetch(PDO::FETCH_OBJ);

// Fetch replies
$replyQuery = $conn->prepare("SELECT * FROM replies WHERE topic_id = :topic_id");
$replyQuery->bindParam(':topic_id', $topic_id);
$replyQuery->execute();
$allReplies = $replyQuery->fetchAll(PDO::FETCH_OBJ);

// Handle new reply submission
if (isset($_POST['submitReply'])) {
    $reply = $_POST['reply'];
    $user_id = $_SESSION['user_id'];
    $user = $_SESSION['username'];
    $user_img = $_SESSION['user_img'];
    $reply_image = '';

    // Check if file was uploaded
    if (isset($_FILES['reply_image']) && $_FILES['reply_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "reply.Upload/";
        $file_name = basename($_FILES["reply_image"]["name"]);
        $target_file = $target_dir . $file_name;

        // Validate the file as an image
        if (getimagesize($_FILES["reply_image"]["tmp_name"]) !== false) {
            if (move_uploaded_file($_FILES["reply_image"]["tmp_name"], $target_file)) {
                $reply_image = $file_name; // Store only the file name
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            }
        } else {
            echo "<script>alert('File is not an image.');</script>";
        }
    }

    // Insert new reply
    if (empty($reply)) {
        echo "<script>alert('One or more inputs are empty');</script>";
    } else {
        $insert = $conn->prepare("INSERT INTO replies (reply, user_id, user_name, user_img, topic_id, reply_img) VALUES (:reply, :user_id, :user, :user_img, :topic_id, :reply_img)");
        $insert->execute([
            ":reply" => $reply,
            ":user_id" => $user_id,
            ":user" => $user,
            ":user_img" => $user_img,
            ":topic_id" => $topic_id,
            ":reply_img" => $reply_image
        ]);

        // Redirect to the topic page
        header("Location: " . APPURL . "/topic/topic.php?topic_id=" . $topic_id);
        exit();
    }
}
?>

<div class="main-container">
    <div class="post">
        <!-- Post Header -->
        <div class="post-header">
            <?php if ($singleTopic): ?>
                <div class="post-info">
                    <img src="../img/<?php echo htmlspecialchars($singleTopic->user_img); ?>" alt="User Logo">
                    <div class="text-info">
                        <span><a href="<?php echo APPURL;?>/users/profile.php?profile_id=<?php echo htmlspecialchars($singleTopic->user); ?>"><?php echo htmlspecialchars($singleTopic->user); ?></a></span>
                        <span><?php echo date('M/d/Y', strtotime($singleTopic->created_at)); ?></span>
                        <span><?php echo htmlspecialchars($singleTopic->category); ?></span>
                    </div>
                </div>
                <div class="categories">
                    <?php if (isset($_SESSION['username']) && $singleTopic->user == $_SESSION['username']): ?>
                        <div class="user-dropdown">
                    <img src="<?php echo APPURL;?>/assets/icons/three-dots.png" alt="User" class="user-logo">
                    <div class="user-dropdown-content">
                        <a href="update.Topic.php?topic_id=<?php echo $singleTopic->topic_id; ?>">Update</a>
                        <a href="delete.Topic.php?topic_id=<?php echo $singleTopic->topic_id; ?>">Delete</a>    
                        </div>
                        </div>
                    <?php endif; ?>
                    
                    
                </div>
            </div>
            <!-- Post Description -->
            <div class="post-description">
                <h3><?php echo htmlspecialchars($singleTopic->title); ?></h3>
                <?php echo nl2br(htmlspecialchars($singleTopic->body)); ?>
            </div>
            <!-- Post Image -->
            <?php if (!empty($singleTopic->topic_img)): ?>
                <img src="topic.Upload/<?php echo htmlspecialchars($singleTopic->topic_img); ?>" alt="Post Image" class="post-image">
            <?php endif; ?>
        <?php else: ?>
            <p>No topic found or Deleted</p>
        <?php endif; ?>

        <!-- Reply Section -->
        <?php if (isset($_SESSION['username'])): ?>
            <form class="reply-input" action="topic.php?topic_id=<?php echo htmlspecialchars($topic_id); ?>" method="POST" enctype="multipart/form-data">
                <textarea name="reply" placeholder="Write a reply..." required></textarea>
                <div class="reply-buttons">
                    <label for="file-upload" class="attach-image-button">
                        <img src="../assets/icons/image.png" alt="Attach Image">
                    </label>
                    <input type="file" name="reply_image" id="file-upload" style="display: none;">
                    <button type="submit" name="submitReply">Reply</button>
                </div>
            </form>
        <?php endif; ?>

        <!-- View Comments Section -->
        <div class="view-comments">
            <span>All Comments</span>
        </div>

        <!-- Replies Section -->
        <ul class="replies-list">
            <?php foreach ($allReplies as $reply): ?>
                <li class="reply">
                    <div class="post-header">
                        <div class="post-info">
                            <img src="../img/<?php echo htmlspecialchars($reply->user_img); ?>" alt="User Logo">
                            <div class="text-info">
                                <span><?php echo htmlspecialchars($reply->user_name); ?></span>
                                <span><?php echo date('m-d-y', strtotime($reply->created_at)); ?></span>
                                </div>
                                </div>
                                <?php if (isset($_SESSION['username']) && $reply->user_id == $_SESSION['user_id']): ?>
                                    
                            <div class="user-dropdown">
                    <img src="<?php echo APPURL;?>/assets/icons/three-dots.png" alt="User" class="user-logo">
                    <div class="user-dropdown-content">
                            <a href="../reply/delete.Reply.php?rep_id=<?php echo $reply->rep_id; ?>">Delete</a>
                            <a href="../reply/update.Reply.php?rep_id=<?php echo $reply->rep_id; ?>">Update</a>
                            </div>
                            </div>
                        <?php endif; ?>    
                        </div>
                       
                    
                    <div class="reply-content">
                        <span class="reply-text"><?php echo nl2br(htmlspecialchars($reply->reply)); ?></span>
                        <?php if (!empty($reply->reply_img)): ?>
                            <img src="reply.Upload/<?php echo htmlspecialchars($reply->reply_img); ?>" alt="Reply Image" class="reply-image">
                        <?php endif; ?>
                        
                </li>
            <?php endforeach; ?>
             </div>
        </ul>
    </div>
   
</div>
