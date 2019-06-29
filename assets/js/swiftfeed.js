$(document).ready(function() {
    $('#submit_profile_post').click(function() {
        $.ajax({
            method: 'POST',
            url: 'include/handlers/ajax_submit_profile_post.php',
            data: $('form.profile_post').serialize(),
            success: function(message) {
                console.log(message)
                $('#post_form').modal('hide')
                location.reload();
            },
            error: function() {
                alert('Failure')
            }
        })
    })

    $('.button_holder').on('click', function() {
        document.search_form.submit();
    })

    $('#search_text_input').focus(function() {
        if (window.matchMedia("(min-width: 800px)").matches) {
            $(this).animate({width: '250px  '}, 500);
        }
    })

    $(document).click(function(e) {
        if (e.target.class != 'search_results' && e.target.id != 'search_text_input') {
            $('.search_results_footer').html('');
            $('.search_results_footer').toggleClass('search_results_footer_empty');
            $('.search_results_footer').toggleClass('search_results_footer');
        }

        if (e.target.class != 'dropdown_data_window') {
            $('.dropdown_data_window').html('');
            $('.dropdown_data_window').css({'padding': '0px', 'height': '0px', 'border': 'none'})
        }
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
    console.log($('.dropdown_data_window').css('height'))
    if ($('.dropdown_data_window').css('height') == "0px") {
        console.log(2)
        var pageName;

        if (type == 'notification ') {
            pageName = 'ajax_load_notifications.php';
            $('span').remove('#unread_notification');
        } else if (type == 'message') {
            pageName = "ajax_load_messages.php";
            $('span').remove('#unreaded_message');
        }

        var ajaxReq = $.ajax({
            method: 'POST',
            url: 'include/handlers/' + pageName,
            data: 'page=1&userLoggedIn=' + user,
            cache: false,
            success: function(response) {
                $('.dropdown_data_window').html(response);
                $('.dropdown_data_window').css({'padding': '0px', 'height': '280px', 'border': '1px solid #dadada'})
                $('#dropdown_data_type').val(type);
            },
            error: function(error) {
                console.log(error)
            }
        })
    } else {
        $('.dropdown_data_window').html('');
        $('.dropdown_data_window').css({'padding': '0px', 'height': '0px', 'border': 'none'})
    }
}

function getLiveSearchResult(value, user) {
    $.post('include/handlers/ajax_search.php', {query: value, userLoggedIn: user}, function(data) {
        if ($('.search_results_footer_empty')[0]) {
            $('.search_results_footer_empty').toggleClass("search_results_footer");
            $('.search_results_footer_empty').toggleClass("search_results_footer_empty");
        }

        $('.search_results').html(data);
        $('.search_results_footer').html('<a href="search.php?q=' + value +'">See all results</a>')

        if (data == '') {
            $('.search_results_footer').html('');
            $('.search_results_footer').toggleClass('search_results_footer_empty');
            $('.search_results_footer').toggleClass('search_results_footer');
        }
    });
}