<?php
  
  class Post{

    private $user_obj;
    private $con;

    public function __construct($con, $user){
        $this->con = $con;
        $this->user_obj = new User ($con, $user);
    }

    
    public function submitPost($body, $user_to){
        $body = strip_tags($body);
        $body = mysqli_real_escape_string($this->con, $body); // a annotation that escapes single quotes = ';
        $check_empty = preg_replace('/\s+/', '', $body); // delete all empty spaces; 

        if($check_empty != ""){
            

            //current date and time 
            $date_added = date('Y-m-d H:i:s');
            //Get Username
            $added_by = $this->user_obj->getUsername();

            //if user is on own profile, user_to us 'none'
            if($user_to == $added_by){
                $user_to = "none";
            }

            //insert postg to database
            $query = mysqli_query($this->con, "INSERT INTO posts VALUES('', '$body', '$added_by', '$user_to',
            '$date_added', 'no', 'no', '0')");

            $return_id = mysqli_insert_id($this->con);


            //insert notification


            //Update post count for the user
            $num_posts = $this->user_obj->getNumPosts();
            $num_posts++;
            $update_query = mysqli_query($this->con,"UPDATE users SET num_posts = '$num_posts' WHERE username = '$added_by'");
        }
    }

    public function loadPostByFriends($data, $limit){

        $page = $data['page'];
        $userLoggedIn = $this->user_obj->getUsername();


        if($page== 1){
            $start = 0;
        }else{
            $start = ($page - 1) * $limit;
        }
 

        $str = "";
        $data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted ='no' ORDER BY id DESC"); 

        if(mysqli_num_rows($data_query) > 0){

            $num_iterations = 0 ; //number of resulat checked (not necessary posted);
            $count = 1;


            while( $row = mysqli_fetch_array($data_query)){

                $id = $row['id'];
                $body = $row['body'];
                $added_by = $row['added_by'];
                $date_time = $row['date_added'];

                //Prepare user_to string, so it can be include even if not post to a user
                if($row['user_to'] == 'none'){
                    $user_to ="";
                }else{
                    $user_to_obj = new User($this->con, $row['user_to']);
                    $user_to_name = $user_to_obj->getFirstAndLastName();
                    $user_to = "to <a href='". $row['user_to'] ."'>".$user_to_name."</a>";
                }

                //Check if the user that post have an account that is closed
                $added_by_obj = new User($this->con, $added_by);
                
                if($added_by_obj->isClosed()){
                    continue;
                }

                $user_logged_obj = new User($this->con, $userLoggedIn);

                if($user_logged_obj->isFriend($added_by)){
                     
        

                    if($num_iterations++ < $start){
                        continue;
                    }

                    //Once 10 post as been load, break(stop);
                    if($count > $limit){
                        break;
                    }else{
                        $count++;
                    }



                    $user_details_query =  mysqli_query($this->con, "SELECT first_name, last_name, profile_image FROM users
                    WHERE username='$added_by'");

                    $user_row = mysqli_fetch_array($user_details_query);
                    $first_name = $user_row['first_name'];
                    $last_name = $user_row['last_name'];
                    $profile_pic = $user_row['profile_image'];

                    ?>

                    <script>
                        function toggle<?php echo $id;?>(){

                            var target = $(event.target);

                            if(!target.is('a')){
                                var element = document.getElementById("toggleComment<?php echo $id;?>");

                                if(element.style.display == "block"){
                                    element.style.display = "none";
                                }else{
                                    element.style.display = "block";
                                }
                            }
                        };
                                             
                    </script>


                    <?php

                        $comment_check = mysqli_query($this->con, "SELECT * FROM comments WHERE posted_id='$id'");
                        $comment_check_num = mysqli_num_rows($comment_check);



                    //Timeframe
                    $date_time_now = date("Y-m-d H:i:s");
                    $start_date = new DateTime($date_time); // Time posted
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


                    $str .= "<div class ='status_post' onClick='javascript:toggle$id()'>
                                <div class='post_profile_pic'>
                                    <img src='$profile_pic' width='80'>
                                </div>
                                <div class='posted_by' style='color:#ACACAC;'>
                                <a href='$added_by' style='font-size:14px; font-weight:bold'>$first_name $last_name</a> $user_to &nbsp;&nbsp;$time_message 
                                </div>
                                <div class='post_body'>
                                $body
                                <br>
                                <br>
                                </div>
                                <br>
                                <br>
                            </div>
                         
                            <div class='newsfeedPostOption' style='color:#337ab7; font-weight: 700; margin:5px 0;'>
                                Comments ($comment_check_num)&nbsp;&nbsp;&nbsp;
                                <iframe src='like.php?post_id=$id' scrolling='no'></iframe>
                            </div>
                             
                            <div class='post_comment' id='toggleComment$id' style='display:none;'>
                              <iframe src='comment_frame.php?post_id=$id' class='comment_iframe' frameborder='0' style='width:100%; min-height:250px;'></iframe>
                            </div>
                            <hr style='border-top: 2px solid rgba(0, 0, 0, 0.1) !important;'>";

                } // closed if-isfriend

            
            } // close of while

            if($count > $limit){
                   $str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'>
                            <input type='hidden' class='noMorePosts' value='false'>";
            }else{
                 $str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: centre;'> No more posts to show! </p>";
            } 
                    
        
        
        } // close if-statement
        echo $str;

    }

  }

?>