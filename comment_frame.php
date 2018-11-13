
<?php
    // include("includes/header.php");
    require 'config/database.php';
    include("includes/classes/User.php");
    include("includes/classes/Post.php");

    if(isset($_SESSION['username'])){
        
        $userLoggedIn = $_SESSION['username'];
        $user_details_query = mysqli_query($conn,"SELECT * FROM users WHERE username='$userLoggedIn'");
        $user = mysqli_fetch_array($user_details_query);


    }else{
        header("Location: register.php");
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <!-- Custom stylesheet-->
     <link rel="stylesheet" href="assets/css/style.css">
</head>

<style type="text/css">

*{
    font-size: 12px;
    font-family: Arial, Sans-serif;
}

.comment__section a{
    color: #337ab7;
    text-decoration: none;
    font-size: 12px;
}

.comment__section img{
    margin: 0 3px 3px 3px;
    float:left;
    width: 45px;
    height: 45px;
    border-radius: 5px;
}
/* #comment__iframe{
    width:100%;
    min-height:250px;
} */

#comment_form textarea{
    border-color: #D3D3D3;
    width: 85%;
    height: 35px;
    border-radius: 5px;
    color:#616060;
    font-size: 14px;
    margin: 3px 3px 3px 5px;
}
#comment_form input[type="submit"]{
    border: none;
    background: #20aae5;
    color: #fff;
    border-radius: 5px;
    width: 13%;
    height: 37px;
    margin-top: 5px;
    position: absolute;
    font-family: "bellota-BoldItalic", sans-serif;
    text-shadow: #7386E2 0.5px 0.5px 0px;
    font-size: 14px;
}

.newsfeedPostOption{
    padding: 0;
    text-decoration: none;
    color: #337ab7;
}

</style>

<body>

    <script>

    
       function toggle(){
           var element = document.getElementById("comment_section");

           if(element.style.display == "block"){
               element.style.display = "none";
           }else{
            element.style.display = "block";
           }
       };
    </script>

    <?php
 
        // Get the id from url:
       if(isset($_GET['post_id'])){
           $post_id = $_GET['post_id'];

       }

       $user_query = mysqli_query($conn, "SELECT added_by, user_to FROM posts WHERE id='$post_id'");
       $row = mysqli_fetch_array($user_query);

       $posted_to = $row['added_by'];

       if(isset($_POST['postComment'.$post_id])){
            $post_body = $_POST['post_body'];
            $post_body = mysqli_escape_string($conn, $post_body);
            $date_time_now = date('Y-m-d H:i:s');

            $insert_post = mysqli_query($conn, "INSERT INTO comments VALUES('', '$post_body','$userLoggedIn','$posted_to','$date_time_now','no','$post_id')");

            echo "<p> Comment Posted!!</p>";
       }

    ?>
    
    <form action="comment_frame.php?post_id=<?php echo $post_id; ?>" id="comment_form" name="postComment<?php echo $post_id; ?>" method="POST">
        <textarea name="post_body"></textarea>
        <input type="submit" name="postComment<?php echo $post_id; ?>" value="Submit">
    </form>

    <!-- Load the posts--->
    <?php 

        $getComments = mysqli_query($conn, "SELECT * FROM comments WHERE posted_id='$post_id' ORDER BY id ASC");
        $count = mysqli_num_rows($getComments);

        if($count != 0){
            while($comments = mysqli_fetch_array($getComments)){

                $comment_body = $comments['post_body'];
                $post_to = $comments['posted_to'];
                $posted_by = $comments['posted_by'];
                $date_addded = $comments['date_added'];
                $removed = $comments['removed'];

                //Timeframe
                $date_time_now = date("Y-m-d H:i:s");
                $start_date = new DateTime($date_addded); // Time posted
                $end_date = new DateTime($date_time_now); // Current Time 
                $interval = $start_date->diff($end_date); // Different between the date

                if($interval-> y >= 1){
                    if($interval == 1){
                        $time_message = $interval-> y . "year ago"; // posted 1 year
                    }else{
                        $time_message = $interval-> y . "years ago"; // posted 1+? years
                    }
                }
                else if ($interval-> m >= 1){

                    if($interval-> m  == 1){
                        $time_message = $interval->m . "month"; 
                    }
                    else{
                        $time_message = $interval->m . "month"; 
                    }
        
                    if($interval-> d  == 1){
                        $days = $interval-> d . "ago"; 
                    }
                    else if ($interval-> d == 1){
                        $days = $interval->d . " day ago"; 
                    }
                    else{
                        $days = $interval->d . " days ago"; 
                    } 

                }

                else if($interval->d >=1){
                    
                    if($interval-> d  == 1){
                        $time_message  =  "Yesterday"; 
                    }else{
                        $time_message = $interval->d . " days ago"; 
                    }

                }

                else if($interval->h >= 1){
                    if($interval-> h  == 1){
                        $time_message  =  $interval->h . " hour ago"; 
                    }else{
                        $time_message  =  $interval->h . " hours ago"; 
                    }
                }

                else if($interval->i >= 1){
                    if($interval-> i  == 1){
                        $time_message  =  $interval->i . " minute ago"; 
                    }else{
                        $time_message  =  $interval->i . " minutes ago"; 
                    }
                }

                else if($interval->s <= 30){
                    if($interval-> i  == 1){
                        $time_message  =  " just posted"; 
                    }else{
                        $time_message  =  " seconds ago"; 
                    }
                }

                $user_obj = new User($conn, $posted_by);

                ?>

                  <div class="comment__section">
                      <br>
                        <a href="<?php echo $posted_by; ?>"  target="_parent">
                            <img src="<?php echo $user_obj->getProfilePic();?>" alt="profile_image" title="<?php echo $posted_by;?>"
                            <a href="<?php echo $posted_by; ?>" target="_parent"> <b> <?php echo $user_obj->getFirstAndLastName();?> </b></a>
                            &nbsp;&nbsp; <?php echo $time_message . "<br>" . $comment_body ;?>
                            <br>
                        </a>
                        <br>
                        <hr>
                    </div>

                <?php

            } //while - close-tag
        }else{
            echo "<center><br><br> No Comment to Show..!</center>";
        }
    ?>

    
</body>
</html>