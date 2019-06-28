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

function getUser(value, user) {
    $.post("include/handlers/ajax_friend_search.php", {
        query: value,
        userLoggedIn: user
    }, function(data) {
        $(".results").html(data);
    })
}

function getDropdownData(user, type) {
    if ($('#dropdown_data_window').css('height') == "0px") {
        var pageName;

        if (type == 'notification ') {
            pageName = 'ajax_load_notifications.php';
        } else if (type == 'message') {
            pageName = "ajax_load_messages.php";
            $('span').remove('#unreaded_message');
        }

        var ajaxReq = $.ajax({
            method: 'POST',
            url: 'include/handlers/' + pageName,
            data: 'page=1&user=' + user,
            cache: false,
            success: function(response) {
                $('#dropdown_data_window').html(response);
                $('#dropdown_data_window').css({'padding': '0px', 'height': '280px;', 'border': '1px solid #dadada'})
                $('#dropdown_data_type').val(type);
            },
            error: function() {

            }
        })
    } else {
        $('#dropdown_data_window').html('');
        $('#dropdown_data_window').css({'padding': '0px', 'height': '0px;', 'border': 'none'})
    }
}