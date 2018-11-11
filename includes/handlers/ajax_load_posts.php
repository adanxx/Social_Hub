<?php  
   include('../../config/database.php');
   include('../classes/User.php');
   include('../classes/Post.php');


   $limit = 10; // number of tpost to be load per call;


   $posts =  new Post($conn, $_REQUEST['userLoggedIn']);
   $posts-> loadPostByFriends($_REQUEST, $limit);
?>