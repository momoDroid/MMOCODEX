<?php
    
    $topic = $conn->query("SELECT COUNT(*) AS all_topics from topic");
    $topic->execute();

    $allTopics = $topic->fetch(PDO::FETCH_OBJ);


     //preparing queries for topic
     $categories = $conn->query("SELECT categories.cat_id AS cat_id, categories.cat_name AS cat_name, COUNT(topic.category) AS count_category FROM categories LEFT JOIN topic ON categories.cat_name = topic.category GROUP BY(categories.cat_id);");
     //GROUP BY(topic.category)

     $categories->execute();
     $allCategories = $categories->fetchAll(PDO::FETCH_OBJ);


     //Forum Statistics

        // users count
     $users = $conn->query("SELECT COUNT(*) AS count_users from user");

     $users->execute();

     $allUsers = $users->fetch(PDO::FETCH_OBJ);

        // topic count
        $countTopic = $conn->query("SELECT COUNT(*) AS count_topic from topic");

        $countTopic->execute();
   
        $count_allTopic = $countTopic->fetch(PDO::FETCH_OBJ);

            // categories count
            $countCategories = $conn->query("SELECT COUNT(*) AS count_categories from categories");

            $countCategories->execute();

            $count_Allcategories = $countCategories->fetch(PDO::FETCH_OBJ);
// recomended topics
$topicQuery = $conn->prepare("
    SELECT topic.*, COUNT(replies.rep_id) AS reply_count
    FROM topic 
    LEFT JOIN replies ON topic.topic_id = replies.topic_id 
    GROUP BY topic.topic_id 
    ORDER BY reply_count DESC 
    LIMIT 5
");
$topicQuery->execute();
$recommendedTopics = $topicQuery->fetchAll(PDO::FETCH_ASSOC);


?>
 


<!--
<h3><a href="<?php echo APPURL; ?>">All Topics <span><?php echo $allTopics->all_topics; ?></span></a></h3><br>
<?php foreach($allCategories as $cat): ?> 
<a href="<?php echo APPURL; ?>/categories/show.Categories.php?cat_name=<?php echo $cat->cat_name; ?>"><?php echo $cat->cat_name; ?> <span><?php echo $cat->count_category; ?></span></a> <br>
<?php endforeach; ?>


<h3>Forum Statistics</h3>
<h4>Total Number of user <span><?php echo $allUsers->count_users; ?></span></h1>
<h4>Total Number of topic <span><?php echo $count_allTopic->count_topic; ?></span></h1>
<h4>Total Number of categories <span><?php echo $count_Allcategories->count_categories; ?></span></h1>
</body>
</html>

-->
 
 <!-- Footer -->
 <aside class="sidebar">
            <div class="recommendations">
            <h3>Recommendations</h3>
<?php foreach($recommendedTopics as $recommendTopic): ?> 
    <h4><a href="<?php echo APPURL; ?>/topic/topic.php?topic_id=<?php echo $recommendTopic['topic_id']?>"><?php echo $recommendTopic['title']; ?></a></h4><span>replies: <?php echo $recommendTopic['reply_count']; ?></span>
<?php endforeach; ?>
            </div>
            <div class="games">
                <h3>Game List</h3>
                <?php foreach($allCategories as $cat): ?> 
                <a href="<?php echo APPURL; ?>/categories/show.Categories.php?cat_name=<?php echo $cat->cat_name; ?>"><?php echo $cat->cat_name; ?></a> <span><?php echo $cat->count_category; ?></span> </p>
                <?php endforeach; ?>
            </div>
            <div class="stats">
                <h3>Statistics</h3>
                <p>Users: <?php echo $allUsers->count_users; ?></p>
                <p>Post: <?php echo $count_allTopic->count_topic; ?></p>
                <p>Games: <?php echo $count_Allcategories->count_categories; ?></p>
            </div>
           
           

           
        </aside>
    </div>
</body>
</html>