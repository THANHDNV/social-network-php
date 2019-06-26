$(document).ready(function() {
    $('#submit_profile_post').click(function() {
        $.ajax({
            method: 'POST',
            url: 'include/handlers/ajax_submit_profile_post.php',
            data: $('form.profile_post').serialize(),
            success: function(message) {
                console.log(message)
                $('#post_form').modal('hide')
                // location.reload();
            },
            error: function() {
                alert('Failure')
            }
        })
    })
})