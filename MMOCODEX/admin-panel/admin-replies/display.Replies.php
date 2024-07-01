<?php

require"../admin-layout/header.php";
        require"../../config/config.php";

    //avoid to bypass using url link
    if(!isset($_SESSION["admin_name"])){
      header("location: ".ADMINURL."/admin-auth/admin.Login.php");
      }
        //admin query
        $repQuery = $conn->query("SELECT * FROM replies");

        $repQuery->execute();

        //fetch to display admin
        $fetchRep = $repQuery->fetchAll(PDO::FETCH_OBJ);
        ?>



<section>
        <table>
        <tr>
            <th>#</th>
            <th>Repy</th>
            <th>User Image</th>
            <th>User </th>
            <th>Go to Topic </th>
            <th>Created At</th>
            <th></th>
        </tr>
        <?php foreach($fetchRep AS $displayRep):?>
        <tr>
            <td><?php echo $displayRep->rep_id; ?></td>
            <td><?php echo $displayRep->reply; ?></td>
            <td><img src="../../img/<?php echo  $displayRep->user_img;; ?>" alt="" style=" vertical-align: middle; width: 50px; height: 50px; border-radius: 50%;"></h2></td>
            <td><?php echo $displayRep->user_name; ?></td>
            <td><?php echo $displayRep->created_at; ?></td>
            <td><button><a href="http://localhost/MMOCODEX_TEST/topic/topic.php?topic_id=<?php echo $displayRep->topic_id; ?>" target="_blank">Go to Topic</a></button></td>
            <td><button><a href="delete.Replies.php?rep_id=<?php echo $displayRep->rep_id; ?>">Delete</a></button></td>

        </tr>
        <?php endforeach; ?>
        </table>
        </section>