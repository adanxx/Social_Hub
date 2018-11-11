<?php
    require 'config/database.php';

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
    <title>The_Social_Hub</title>
    <!-- CSS-->
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- JAVASCRIPT-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="assets/js/bootstrap.js"></script>
   
</head>
<body>
  
  <div class="top_bar">
       <div class="logo">
            <a href="index.php">SocialHub!</a>
       </div>

       <nav>
            <a href="<?php echo $userLoggedIn ?>"><?php echo $user['username'] ?></a>
            <a href="#"><i class="fas fa-home"></i></a>
            <a href="#"><i class="fas fa-envelope"></i></a>
            <a href="#"><i class="fas fa-bell"></i></i></a>
            <a href="#"><i class="fas fa-users"></i></a>
            <a href="#"><i class="fas fa-cogs"></i></a> 
            <a href="includes/handlers/logout.php"><i class="fas fa-sign-out-alt"></i></a>     
       </nav>
  </div>

  <div class="wrapper"> <!-- Begin Container block for User_Information --> 

