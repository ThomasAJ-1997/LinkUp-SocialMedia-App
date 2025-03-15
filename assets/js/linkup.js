$(document).ready(function () {
    // AJAX Button call for profile posts.
    $('#submit_profile_post').click(function (){
       $.ajax({
         type: "POST",
         url: "includes/handlers/ajax_submit_profile_post.php",
         data: $('form.profile_post').serialize(),
         success: function (msg) {
             $("#post_form").modal('hide');
             location.reload();
         },
           error: function(requestObject, error, errorThrown) {
               alert(error);
               alert(errorThrown);

           }
       });
    });
});