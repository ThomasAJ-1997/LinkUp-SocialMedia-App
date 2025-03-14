<?php include("includes/header.php");
include ("classes/User.php");
include ("classes/Post.php");

if (isset($_POST['post'])) {
    $post = new Post($conn, $userLoggedIn);
    $post->submitPost($_POST['post_text'], 'none');
    header("location: index.php");

}
?>

<div class="user_details column">
    <a href="<?php echo $userLoggedIn ?>"><img src="<?php echo $user['profile_pic'] ?>"></a>
    <div class="user_details_left_right">
    <a href="<?php echo $userLoggedIn ?>">
    <?php echo $user['first_name'] . " " . $user['last_name'] ?>
    </a>
    <br>
    <?php echo "Posts: " . $user['num_posts']. "<br>";
    echo "Likes: " . $user["num_likes"]?>
    </div>
</div>

<div class="main_column column">
    <form class="post_form" action="index.php" method="POST">
        <textarea name="post_text" id="post_text" placeholder="Share your coding day!"></textarea>
        <input type="submit" name="post" id="post_button" value="Publish">
        <hr>
        <br>
    </form>

    <div class="posts_area">
    <img id="#loading" class="loading-image" src="assets/images/loading.gif">
    </div>
</div>


<script>
    $(function(){
        let userLoggedIn = '<?php echo $userLoggedIn; ?>';
        let inProgress = false;
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
            $('.loading-image').show();
            let page = $('.posts_area').find('.nextPage').val() || 1; //If .nextPage couldn't be found, it must not be on the page yet (it must be the first time loading posts), so use the value '1'
            $.ajax({
                url: "includes/handlers/ajax_load_posts.php",
                type: "POST",
                data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
                cache:false,
                success: function(response) {
                    $('.posts_area').find('.nextPage').remove(); //Removes current .nextpage
                    $('.posts_area').find('.noMorePosts').remove(); //Removes current .nextpage
                    $('.posts_area').find('.noMorePostsText').remove(); //Removes current .nextpage
                    $('.loading-image').hide();
                    $(".posts_area").append(response);
                    inProgress = false;
                }
            });
        }

        function isElementInView (el) {
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


</div>
</body>

</html>