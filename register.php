<?php
  require 'config/database.php';
   require 'includes/form_handlers/api-register.php';
   require 'includes/form_handlers/api-login.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/css/register_style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="assets/js/register.js"></script>
    <title>Registration_Form</title>

</head>
<body>

      <?php if(isset($_POST["reg_btn"])){ 
            echo '<script>
                        $(document).ready(function(){
                            $("#first").hide();
                            $("#second").show();
                        })
                    </script>';
        } ?>
        <?php if(isset($_POST["log_btn"])){ 
            echo '<script>
                        $(document).ready(function(){
                            $("#second").hide();
                            $("#first").show();
                        })
                    </script>';
        } ?>

    <div class="wrapper">
        <div class="login_box">

        <div class="login_header">
             <h1>SocialHub</h1>
             <p>Login or signup below!!!</p>
        </div>

            <div id="first">  <!-- The Register from Area : Begin-->
                <form action="register.php" method="post">
                    <input type="email" name="log_email" value="<?php if(isset($_SESSION['log_email'])){ echo $_SESSION['log_email']; }?>" placeholder="Enter Your Email.." required>
                    <br>
                    <input type="password" name="log_password" placeholder="Enter Your Password..">
                    <br>
                    <input type="submit" name="log_btn" value="Login">
                    <br>
                    <?php if(in_array("Email or Password is incorret!!!<br>", $error_array))  echo"Email or Password is incorret!!!<br>"; ?>
                    <br>
                    <a href="#" id="signup" class="signup">Don't have Account? Register here!!</a>
                </form>
            </div>

            <div id="second"> <!-- The Register from Area : Begin-->
                    <form action="register.php" method="post">

                        <input type="text" name="reg_fname" placeholder="Enter your FirstName" value = "<?php if(isset($_SESSION['reg_fname'])){ echo $_SESSION['reg_fname']; }?>" required> 
                        <br>  
                        <?php if(in_array("Your first name must be between 2-25 character long <br>", $error_array)) echo "Your first name must be between 2-25 character long <br>";?>

                        <input type="text" name="reg_lname" placeholder="Enter your LastName" value = "<?php if(isset($_SESSION['reg_lname'])){ echo $_SESSION['reg_lname']; }?>" required>   
                        <br>
                        <?php if(in_array("Your last name must be between 2-25 character long <br>", $error_array)) echo "Your last name must be between 2-25 character long <br>" ;?>

                        <input type="email" name="reg_email" placeholder="Enter your Email" value = "<?php if(isset($_SESSION['reg_email'])){ echo $_SESSION['reg_email']; }?>" required>   
                        <br>
                        <?php 
                            if(in_array("Email is already exists <br>", $error_array))  echo "This Email is already exists <br>" ;

                            else if(in_array("Email is invalied Format <br>", $error_array)) echo  "Email is invalied Format <br>";

                            else if(in_array("Email do not Match!! <br>", $error_array)) echo "Email do not Match!! <br>" ;
                            
                        ?>

                        <input type="email" name="reg_email2" placeholder="confirm Email" value = "<?php if(isset($_SESSION['reg_email2'])){ echo $_SESSION['reg_email2']; }?>" required>   
                        <br>
                        <?php 
                            if(in_array("Email is already exists <br>", $error_array))  echo "This Email is already exists <br>" ;

                            else if(in_array("Email is invalied Format <br>", $error_array)) echo  "Email is invalied Format <br>";

                            else if(in_array("Email do not Match!! <br>", $error_array)) echo "Email do not Match!! <br>" ;
                            
                        ?>

                        <input type="password" name="reg_password" placeholder="Enter your Password" required>   
                        <br>
                        <?php 
                            if(in_array("Your password do not match <br>", $error_array))  echo "Your password do not match <br>";

                            else if(in_array("Your password can only can Eng-US characters or number <br>", $error_array)) echo "Your password can only can Eng-US characters or number <br>";

                            else if(in_array( "Your password must be between 5 and 30 characters <br>", $error_array)) echo  "Your password must be between 5 and 30 characters <br>";
                            
                        ?>

                        <input type="password" name="reg_password2" placeholder="confirm Password" required>   
                        <br>
                        <?php 
                            if(in_array("Your password do not match <br>", $error_array))  echo "Your password do not match <br>";

                            else if(in_array("Your password can only can Eng-US characters or number <br>", $error_array)) echo "Your password can only can Eng-US characters or number <br>";

                            else if(in_array( "Your password must be between 5 and 30 characters <br>", $error_array)) echo  "Your password must be between 5 and 30 characters <br>";
                            
                        ?>

                        <input type="submit" name="reg_btn" value="Register" id="reg_btn">
                        <br>

                        <?php if(in_array( "<span style='color: #14C800';>Welcome New User - Go Right Ahead and Login </span>", $error_array))  
                                echo  "<span style='color: #14C800';>Welcome New User - Go Right Ahead and Login </span><br>"; 
                        ?>

                        <a href="#" id="signin" class="signin">Got an Account ? Sign in here!!</a>
                </form>

            </div>         
        </div>
    </div>
</body>
</html>