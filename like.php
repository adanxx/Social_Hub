
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <!-- Custom stylesheet-->
      <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>

 <style type="text/css">
  *{
      font-family: Arial, Helvetica, Sans-serif;
  }
   body{
       background: #fff;
   }
   form{
       position: absolute;
       top: 1px;
   }
 </style>

<body>

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

    // Get the id from url:
    if(isset($_GET['post_id'])){
        $post_id = $_GET['post_id'];
    
    }
 

    $get_likes = mysqli_query($conn, "SELECT * FROM posts WHERE id='$post_id'");
    $row = mysqli_fetch_array($get_likes);
    $total_likes = $row['likes'];
    $user_liked = $row['added_by'];

    $user_details_query = mysqli_query($conn, "SELECT * FROM users WHERE username='$user_liked'");
    $user_row = mysqli_fetch_array($user_details_query);
    $total_user_likes = $user_row['num_likes'];

    //Like button
    if(isset($_POST['like_button'])){

         $total_likes++;
         $query = mysqli_query($conn, "UPDATE posts SET likes ='$total_likes' WHERE id='$post_id'");  //.1 check if the post is update
         $total_user_likes++;
         $user_likes = mysqli_query($conn, "UPDATE users SET num_likes='$total_user_likes' WHERE username='$user_liked'"); //2.check if user update 
         $insert_user = mysqli_query($conn, "INSERT INTO likes VALUES('','$userLoggedIn','$post_id')"); //3. check if the list is update:
         //Insert notification !!
        
    }

    //Unlike button
    if(isset($_POST['Unlike_button'])){
         $total_likes--;
         $query = mysqli_query($conn, "UPDATE posts SET likes ='$total_likes' WHERE id='$post_id'");
         $total_user_likes--;
         $user_likes = mysqli_query($conn, "UPDATE users SET num_likes='$total_user_likes' WHERE username='$user_liked'");
         $delete_user = mysqli_query($conn, "DELETE FROM likes WHERE username='$userLoggedIn' AND post_id='$post_id' ");

         //Insert notification !!
       
    }

    //check for previous likes
    $check_query_likes =  mysqli_query($conn, "SELECT * FROM likes WHERE username='$userLoggedIn' AND post_id='$post_id'");
    $num_rows = mysqli_num_rows($check_query_likes);



    if($num_rows > 0){
        
        echo '<form action="like.php?post_id='.$post_id.'" method="POST" class="frm_post">
                <input type="submit" class="comment_like" name="Unlike_button" value="Unlike">
                    <div class="like_value">
                        '.$total_likes.' Likes
                    </div>
                </form>'
                ;

    }else {
        
            
        echo '<form action="like.php?post_id='.$post_id.'" method="POST" class="frm_post">
                <input type="submit" class="comment_like" name="like_button" value="Like">
                    <div class="like_value">
                        '.$total_likes.' Likes
                    </div>
                </form>';

    }

 
 ?>
  


    
</body>
</html>