<?php
 require"../includes/header.php"; 
 

// Check if the query parameter is set
if (isset($_GET['searchTerm'])) {
    $searchQuery = $_GET['searchTerm'];

    // Prepare the SQL query to search for topics
    $sql = "SELECT 
                topic.topic_id AS topic_id, 
                topic.title AS title, 
                topic.category AS category, 
                topic.user AS user, 
                topic.user_img AS user_img, 
                topic.created_at AS created_at, 
                topic.topic_img AS topic_img,
                COUNT(replies.topic_id) AS count_replies 
            FROM topic 
            LEFT JOIN replies ON topic.topic_id = replies.topic_id 
            WHERE topic.title LIKE :query OR topic.category LIKE :query
            GROUP BY topic.topic_id";
    
    // Prepare and execute the query
    $query = $conn->prepare($sql);
    $query->execute(array(':query' => '%' . $searchQuery . '%'));
    $searchedTopics = $query->fetchAll(PDO::FETCH_OBJ);
} else {
    // If no query parameter is set, handle this case (optional)
    $searchedTopics = [];
}

?>
    


<div class="main-container">
        <section class="post-section">
            <ul>
            <?php foreach($searchedTopics AS $topic):?>
     <li class="post">
            <div class="post-header">
                <div class="post-info">
                    <img src="<?php echo APPURL;?>/img/<?php echo $topic->user_img;?>" alt="User Logo">
                        <div class="text-info">
                            <span><a href="<?php echo APPURL;?>/users/profile.php?profile_id=<?php echo $topic->user; ?>"><?php echo $topic->user;?></a></span>
                            <span><?php echo $topic->category;?></span>
                            <span><?php echo date('M/d/Y', strtotime($topic->created_at));?></span>
                    </div>
                 </div>
            </div>
                <div class="post-description">
                <?php echo $topic->title;?>
                </div>
                <?php if (!empty($topic->topic_img)): ?>
                    <img src="<?php echo APPURL;?>/topic/topic.Upload/<?php echo $topic->topic_img;?>" alt="Post Image" class="post-image">
                    <?php else: ?>
                        <?php endif; ?>  
                    <div class="view-comments">
                    <i><img src="<?php echo APPURL;?>/assets/icons/comment.png" alt=""></i> <!-- Replace with an actual icon if you have an icon font or SVG -->
                <span><a href="<?php echo APPURL;?>/topic/topic.php?topic_id=<?php echo $topic->topic_id?>">View Comments <span><?php echo $topic->count_replies;?></span></a></span>
                        </div>
                    </li>
                                <?php endforeach;?>
            </ul>
        </section>
        <?php include "../includes/footer.php"; ?>

      
   