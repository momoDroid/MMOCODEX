<?php
 require"../includes/header.php"; 


    if(isset($_GET['cat_name'])){
            $cat_name = $_GET['cat_name'];

        $topic = $conn->query("SELECT * from topic WHERE category = '$cat_name'");

        $topic->execute();
        $allTopics = $topic->fetchAll(PDO::FETCH_OBJ);

    }else{
      header("location:".APPURL."/404.php");
   }

    //preparing queries for topic




?>
    
    <!--
    <h1><?php echo $cat_name;?></h1> 
    <?php foreach($allTopics AS $topic):?>
    
   
    <table style=" border-spacing: 30px;">
  <tr>
  
    <th><img src="../img/<?php echo $topic->user_img;?>" alt="Avatar"  style="
  vertical-align: middle;
  width: 50px;
  height: 50px;
  border-radius: 50%;
"></th>
    <th><a href="../topic/topic.php?topic_id=<?php echo $topic->topic_id?>"><?php echo $topic->title;?></a></th>
    <th><a href=""><?php echo $topic->category;?></a></th>
    <th><a href=""><?php echo $topic->created_at;?></a></th>
    
   
  </tr>
  
  <?php endforeach;?>
</table>
    -->
<div class="main-container">
        <section class="post-section">
            <ul>
            <h1><?php echo $cat_name;?></h1> 
            <?php foreach($allTopics AS $topic):?>
     <li class="post">
      
            <div class="post-header">
                <div class="post-info">
                    <img src="../img/<?php echo $topic->user_img;?>" alt="Avatar"  alt="User Logo">
                        <div class="text-info">
                            <span><?php echo $topic->user;?></span>
                            <span><?php echo $topic->category;?></span>
                            <span><?php echo $topic->created_at;?></span>
                    </div>
                 </div>
            </div>
                <div class="post-description">
                <?php echo $topic->title;?>
                </div>
                <?php if (!empty($topic->topic_img)): ?>
                    <img src="../topic/topic.Upload/<?php echo $topic->topic_img;?>" alt="Post Image" class="post-image">
                    <?php else: ?>
                        <?php endif; ?> 
                    <div class="view-comments">
                    <i><img src="../assets/icons/comment.png" alt=""></i> <!-- Replace with an actual icon if you have an icon font or SVG -->
                <span><a href="../topic/topic.php?topic_id=<?php echo $topic->topic_id?>">View Comments </a></span>
                        </div>
                    </li>
                                <?php endforeach;?>
            </ul>
        </section>
        <?php include "../includes/footer.php"; ?>