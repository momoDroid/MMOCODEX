<?php 

require "../includes/header.php";


// Check if the user is logged in, otherwise redirect to the homepage
if (!isset($_SESSION["username"])) {
    header("location: " . APPURL);
    exit();
}

// Initialize variables

// Fetch topic data
if (isset($_GET['profile_id'])) {
    $profile_id = $_GET['profile_id'];
    
    // Display user data for profile
    $userQuery = $conn->prepare("SELECT * FROM user WHERE username = :profile_id");
    $userQuery->execute([':profile_id' => $profile_id]);
    $displayUser = $userQuery->fetch(PDO::FETCH_OBJ);

    if (!$displayUser) {
        // Handle case where user is not found
        echo "<script>alert('User not found');</script>";
        exit();
    }

    // Display the count for topics
    $topicQuery = $conn->prepare("SELECT COUNT(*) AS count_topic FROM topic WHERE user = :profile_id");
    $topicQuery->bindParam(':profile_id', $profile_id);
    $topicQuery->execute();
    $countUserTopic = $topicQuery->fetch(PDO::FETCH_OBJ);

    // Display the count for replies
    $replyQuery = $conn->prepare("SELECT COUNT(*) AS count_reply FROM replies WHERE user_name = :profile_id");
    $replyQuery->bindParam(':profile_id', $profile_id);
    $replyQuery->execute();
    $countUserReply = $replyQuery->fetch(PDO::FETCH_OBJ);

    // Display all topics from the user
    $queryTopic = $conn->prepare("SELECT 
        topic.topic_id AS topic_id, 
        topic.title AS title, 
        topic.category AS category, 
        topic.user AS user, 
        topic.user_img AS user_img,
        topic.topic_img AS topic_img,
        topic.created_at AS created_at, 
        COUNT(replies.topic_id) AS count_replies 
        FROM topic LEFT JOIN replies ON topic.topic_id = replies.topic_id 
        WHERE topic.user = :profile_id 
        GROUP BY topic.topic_id");

    // Bind the profile ID parameter
    $queryTopic->bindParam(':profile_id', $profile_id);

    // Execute the query
    $queryTopic->execute();

    // Fetch all rows
    $userTopics = $queryTopic->fetchAll(PDO::FETCH_OBJ);
} else {
    echo "<script>alert('Profile ID is not set');</script>";
    exit();
}
?>

<!--

<img src="../img/<?php echo htmlspecialchars($displayUser->avatar); ?>" alt="User Image" style="vertical-align: middle; width: 300px; height:300px;">
<h1>Username: <span><?php echo htmlspecialchars($displayUser->username); ?></span></h1>
<h2>Name: <span><?php echo htmlspecialchars($displayUser->name); ?></span></h2>
<h2>About </h2><br>
<p><?php echo nl2br(htmlspecialchars($displayUser->about)); ?></p>

<h3>Number of Topics: <span><?php echo $countUserTopic->count_topic; ?></span></h3>
<h3>Number of Replies: <span><?php echo $countUserReply->count_reply; ?></span></h3>

<br><br>

<h1>Topics</h1>
<?php if (count($userTopics) > 0): ?>
    <table style="border-spacing: 30px;">
        <?php foreach ($userTopics as $topic): ?>
            <tr>
                <th><img src="../img/<?php echo htmlspecialchars($topic->user_img); ?>" alt="Avatar" style="vertical-align: middle; width: 50px; height: 50px; border-radius: 50%;"></th>
                <th><a href="../topic/topic.php?topic_id=<?php echo htmlspecialchars($topic->topic_id); ?>"><?php echo htmlspecialchars($topic->title); ?></a></th>
                <th><a href="#"><?php echo htmlspecialchars($topic->category); ?></a></th>
                <th><?php echo htmlspecialchars($topic->created_at); ?></th>
                <th><?php echo htmlspecialchars($topic->count_replies); ?></th>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No topics found for this user.</p>
<?php endif; ?>
-->
    <div class="main-container">
        <section class="post-section">
            <div class="profile-section">
            <div class="profile-header">
                <img src="../img/<?php echo htmlspecialchars($displayUser->avatar); ?>" alt="Profile Picture" class="profile-picture">
                <div class="profile-info">
                    <h1><?php echo htmlspecialchars($displayUser->username); ?></h1>
                    <p><?php echo nl2br(htmlspecialchars($displayUser->about)); ?></p>
                    <div class="profile-stats">
                        <p>Number of Posts: <?php echo $countUserTopic->count_topic; ?></p>
                        <p>Number of Replies: <?php echo $countUserReply->count_reply; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="profile-content">
            <h2>Recent Posts</h2>
            <?php if (count($userTopics) > 0): ?>
        </div>
            <!-- Post-->
            <ul>
            <?php foreach ($userTopics as $topic): ?>
                <li class="post">
                    <div class="post-header">
                        <div class="post-info">
                            <img src="../img/<?php echo htmlspecialchars($topic->user_img); ?>" alt="User Logo">
                            <div class="text-info">
                                <span><?php echo htmlspecialchars($topic->user); ?></span>
                                <span><?php echo htmlspecialchars($topic->created_at); ?></span>
                                <span><?php echo htmlspecialchars($topic->category); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="post-description">
                    <?php echo htmlspecialchars($topic->title); ?>
                    </div>
                    <?php if (!empty($topic->topic_img)): ?>
                    <img src="../topic/topic.Upload/<?php echo $topic->topic_img;?>" alt="Post Image" class="post-image">
                        <?php else: ?>
                        <?php endif; ?>
                    <div class="view-comments">
                        <i><img src="../assets/icons/comment.png" alt=""></i> <!-- Replace with an actual icon if you have an icon font or SVG -->
                        <a href="../topic/topic.php?topic_id=<?php echo $topic->topic_id?>">View Comments <span><?php echo htmlspecialchars($topic->count_replies); ?></span></a>
                    </div>
                </li>
                <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No topics found for this user.</p>
<?php endif; ?>
            </ul>
        </section>
 <?php require "../includes/footer.php"; ?>