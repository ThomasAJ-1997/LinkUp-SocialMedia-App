$(document).ready(function (){
   // On click signup and hide login/ show registration
    $("#signup").click(function () {
        $("#first").slideUp("slow", function () {
            $("#second").slideDown("slow")
        })
    });
    // On click
    $("#signIn").click(function () {
        $("#second").slideUp("slow", function () {
            $("#first").slideDown("slow")
        })
    });
});