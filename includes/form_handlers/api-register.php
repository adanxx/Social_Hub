<?php


  //Declaring varibale to prevent errors
  $fname       = "";
  $lname       = "";
  $email       = "";
  $email2      = "";
  $password    = "";
  $password2   = "";
  $date        = "";
  $error_array = array();

    if(isset($_POST['reg_btn'])){
      
        //Register form values

        //FirstName
        $fname = strip_tags($_POST['reg_fname']); //strips_tags is removes any html tags from the data - security for sql-injections
        $fname = str_replace(' ','', $fname); // remove any empty space between text 
        $fname = ucfirst(strtolower($fname)); // make letter to lower character except for the first letter in text to Upper character
        $_SESSION['reg_fname'] = $fname;
        
        //LastName
        $lname = strip_tags($_POST['reg_lname']); //strips_tags is removes any html tags from the data - security for sql-injections
        $lname = str_replace(' ','', $lname); // remove any empty space between text 
        $lname = ucfirst(strtolower($lname)); // make letter to lower character except for the first letter in text to Upper character
        $_SESSION['reg_lname'] = $lname;

         //Email
         $email = strip_tags($_POST['reg_email']); //strips_tags is removes any html tags from the data - security for sql-injections
         $email = str_replace(' ','', $email); // remove any empty space between text 
         $email = ucfirst(strtolower($email)); // make letter to lower character except for the first letter in text to Upper character
         $_SESSION['reg_email'] = $email;

        //Confirm Email
        $email2 = strip_tags($_POST['reg_email2']); //strips_tags is removes any html tags from the data - security for sql-injections
        $email2 = str_replace(' ','', $email2); // remove any empty space between text 
        $email2 = ucfirst(strtolower($email2)); // make letter to lower character except for the first letter in text to Upper character
        $_SESSION['reg_email2'] = $email2;


        //password
        $password = strip_tags($_POST['reg_password']); //strips_tags is removes any html tags from the data - security for sql-injections
        // Confirm password
        $password2 = strip_tags($_POST['reg_password2']); //strips_tags is removes any html tags from the data - security for sql-injections

        $date = date('Y-m-d'); // Set the current date

        //1.Check if emails are a match
        if( $email == $email2){
            //2.Check if email is valid format
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                    
                $email = filter_var($email, FILTER_VALIDATE_EMAIL);

                //3. check if email already exsist
                $e_check = mysqli_query($conn, "SELECT email From users WHERE email ='$email'");
                //count number of row
                $num_rows = mysqli_num_rows($e_check);

                if($num_rows > 0){
                    array_push($error_array, "Email is already exists <br>" );
                }

            }else{
                array_push($error_array, "Email is invalied Format <br>");
            }

        }else{
           
            array_push($error_array,"Email do not Match!! <br>");  
        }

        if(strlen($fname) > 25 or strlen($fname) < 2){

            array_push($error_array, "Your first name must be between 2-25 character long <br>");           
        }
        if (strlen($lname) > 25 or strlen($lname) < 2){
     
            array_push($error_array, "Your last name must be between 2-25 character long <br>");   
        }

        if($password != $password2){
            array_push($error_array, "Your password do not match <br>"); 
        }
        else{
            if(preg_match('/[^A-Za-z0-9]/', $password)){   // the preg_match is regular expression syntax
           
               array_push($error_array, "Your password can only can Eng-US characters or number <br>"); 
            }
        }

        if(strlen($password) > 30  || strlen($password) < 6 ){
     
             array_push($error_array, "Your password must be between 5 and 30 characters <br>"); 
        }

        if(empty($error_array)){
            $password = md5($password);  // unique encrypt function:

            //Generate username by concatenating firstName and Lastname
            $username = strtolower($fname . "_" . $lname);

            $check_username_query = mysqli_query($conn, "SELECT username From users WHERE username = '$username'");

            $i = 0;
            //if user exsist add number to username;
            while(mysqli_num_rows($check_username_query) != 0){ // a while loop that will rerun true if the the newmade username + number exsist, and will increment until false.
              $i++;
              $username =  $username . "_" . $i;

              $check_username_query = mysqli_query($conn, "SELECT username From users WHERE username = '$username'");
            }

            $rand = rand(1,2); // random between 1 and 2;

            if($rand == 1){
                 $profile_image = "assets/images/profile_images/default/eggplant.jpg";
            }else{
                $profile_image = "assets/images/profile_images/default/eggplant2.jpg";
            }
           
            $query = mysqli_query($conn, "INSERT into users VALUES(NULL, '$fname', '$lname', '$username', '$email', '$password', '$date', '$profile_image', '0', '0', 'no', ',')");

            array_push($error_array, "<span style='color: #14C800';>Welcome New User - Go Right Ahead and Login </span>");

            //Clear Session variables
            $_SESSION['reg_fname'] = "";
            $_SESSION['reg_lname'] = "";
            $_SESSION['reg_email'] = "";
            $_SESSION['reg_email2'] = "";

        } 
    }


?>