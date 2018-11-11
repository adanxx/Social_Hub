<?php
  include("includes/header.php");
  include("includes/classes/User.php");
  include("includes/classes/Post.php");

  if(isset($_POST['post'])){
      $post = new Post ($conn, $userLoggedIn);
      $post->submitPost($_POST['post_text'], 'none');
  }

?>
  <div class="user_details column">
        <a href="<?php echo $userLoggedIn ?>"> <img src="<?php echo $user['profile_image'] ?>" alt="User_Profile_Image"></a>
        
        <div class="user_details_left_right">

            <a href="<?php echo $userLoggedIn ?>">
                <?php echo $user['first_name']." ". $user['last_name'] ?>
            </a>
            <br>
            <?php echo "Post : ". $user['num_posts']. "<br>";
                echo "Likes : ". $user['num_likes']. "<br>"   
           ?>

        </div>
  </div>

   <div class="main_column column">
        <form action="index.php" method="POST" class="post_form">
            <textarea name="post_text" id="post_text" placeholder="Got Something to Say?.."></textarea>  
             <input type="submit" value="Post" name="post" id="post_btn">
             <hr>
        </form>


      <div class="posts_area"></div>

      <img id="loading" src="assets/images/icons/loading.gif">

   </div>


   

   <script>

        $(function(){
                var userLoggedIn = '<?php echo $userLoggedIn; ?>';
                var inProgress = false;
                loadPosts(); //Load first posts
                $(window).scroll(function() {
                    var bottomElement = $(".status_post").last();
                    var noMorePosts = $('.posts_area').find('.noMorePosts').val();
                    // isElementInViewport uses getBoundingClientRect(), which requires the HTML DOM object, not the jQuery object. The jQuery equivalent is using [0] as shown below.
                    if (isElementInView(bottomElement[0]) && noMorePosts == 'false') {
                        loadPosts();
                    }
                });
                function loadPosts() {
                    if(inProgress) { //If it is already in the process of loading some posts, just return
                        return;
                    }
                    
                    inProgress = true;
                    $('#loading').show();
                    var page = $('.posts_area').find('.nextPage').val() || 1; //If .nextPage couldn't be found, it must not be on the page yet (it must be the first time loading posts), so use the value '1'
                    $.ajax({
                        url: "includes/handlers/ajax_load_posts.php",
                        type: "POST",
                        data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
                        cache:false,
                        success: function(response) {
                            $('.posts_area').find('.nextPage').remove(); //Removes current .nextpage
                            $('.posts_area').find('.noMorePosts').remove(); 
                            $('.posts_area').find('.noMorePostsText').remove();
                            $('#loading').hide();
                            $(".posts_area").append(response);
                            inProgress = false;
                        }
                    });
                }
                //Check if the element is in view
                function isElementInView (el) {
                        if(el == null) {
                            return;
                        }
                    var rect = el.getBoundingClientRect();
                    return (
                        rect.top >= 0 &&
                        rect.left >= 0 &&
                        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && //* or $(window).height()
                        rect.right <= (window.innerWidth || document.documentElement.clientWidth) //* or $(window).width()
                    );
                }
            });
   </script>
 
 



    </div> <!--Close: Container block for User Information --->  
</body>
</html>