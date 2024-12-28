// For previewing the note image
var input = document.querySelector("#select_note_img");

input.addEventListener("change", preview);

function preview() {
    var fileobject = this.files[0];
    var filereader = new FileReader();

    filereader.readAsDataURL(fileobject);

    filereader.onload = function () {
        var image_src = filereader.result;
        var image = document.querySelector("#note_img");
        image.setAttribute('src', image_src);
        image.setAttribute('style', 'display:');
    }
}

// For following the user
$(".followbtn").click(function () {
    var user_id_v = $(this).data('userId');
    
    var button = this;
    $(button).attr('disabled', true);

    $.ajax({
        url: 'assets/php/ajax.php?follow',
        method: 'post',
        dataType: 'json',
        data: { user_id: user_id_v },
        success: function (response) {
            console.log(response);
            if (response.status) {
                $(button).data('userId', 0);
                $(button).html('<i class="bi bi-check-circle-fill"></i> Followed')
            } else {
                $(button).attr('disabled', false);

                alert('An error occurred. Please try again later.');
            }
        }
    });
});

// For unfollowing the user
$(".unfollowbtn").click(function () {
    var user_id_v = $(this).data('userId');
    var button = this;
    $(button).attr('disabled', true);
    console.log('clicked');
    $.ajax({
        url: 'assets/php/ajax.php?unfollow',
        method: 'post',
        dataType: 'json',
        data: { user_id: user_id_v },
        success: function (response) {
            if (response.status) {
                $(button).data('userId', 0);
                $(button).html('<i class="bi bi-check-circle-fill"></i> Unfollowed')
            } else {
                $(button).attr('disabled', false);

                alert('An error occurred. Please try again later.');
            }
        }
    });
});

// For pinning the note
$(".pin_btn").click(function () {
    var note_id_v = $(this).data('noteId');
    var button = this;
    $(button).attr('disabled', true);
    $.ajax({
        url: 'assets/php/ajax.php?pin',
        method: 'post',
        dataType: 'json',
        data: { note_id: note_id_v },
        success: function (response) {
            console.log(response);
            if (response.status) {

                $(button).attr('disabled', false);
                $(button).hide()
                $(button).siblings('.unpin_btn').show();
                $('#pincount' + note_id_v).text($('#pincount' + note_id_v).text() - (-1));
                // location.reload();

            } else {
                $(button).attr('disabled', false);

                alert('An error occurred. Please try again later.');

            }
        }
    });
});

$(".unpin_btn").click(function () {
    var note_id_v = $(this).data('noteId');
    var button = this;
    $(button).attr('disabled', true);
    $.ajax({
        url: 'assets/php/ajax.php?unpin',
        method: 'post',
        dataType: 'json',
        data: { note_id: note_id_v },
        success: function (response) {

            if (response.status) {

                $(button).attr('disabled', false);
                $(button).hide()
                $(button).siblings('.pin_btn').show();
                // location.reload();
                $('#pincount' + note_id_v).text($('#pincount' + note_id_v).text() - 1);

            } else {
                $(button).attr('disabled', false);

                alert('An error occurred. Please try again later.');
            }
        }
    });
});

// For adding feedback
$(".add-feedback").click(function () {
    var button = this;

    var feedback_v = $(button).siblings('.feedback-input').val();

    if (feedback_v == '') {
        return 0;
    }
    var note_id_v = $(this).data('noteId');
    var cs = $(this).data('cs');
    var page = $(this).data('page');

    $(button).attr('disabled', true);
    $(button).siblings('.feedback-input').attr('disabled', true);
    $.ajax({
        url: 'assets/php/ajax.php?addfeedback',
        method: 'post',
        dataType: 'json',
        data: { note_id: note_id_v, feedback: feedback_v },
        success: function (response) {
            console.log(response);
            if (response.status) {

                $(button).attr('disabled', false);
                $(button).siblings('.feedback-input').attr('disabled', false);
                $(button).siblings('.feedback-input').val('');
                $("#" + cs).prepend(response.feedback);

                $('.nce').hide();
                if (page == 'wall') {
                    location.reload();
                }

            } else {
                $(button).attr('disabled', false);
                $(button).siblings('.feedback-input').attr('disabled', false);

                alert('An error occurred. Please try again later.');

            }
        }
    });
});

var sr = false;

$("#search").focus(function () {
    $("#search_result").show();
});

$("#close_search").click(function () {
    $("#search_result").hide();
});

$("#search").keyup(function () {
    var keyword_v = $(this).val();

    $.ajax({
        url: 'assets/php/ajax.php?search',
        method: 'post',
        dataType: 'json',
        data: { keyword: keyword_v },
        success: function (response) {
            console.log(response);
            if (response.status) {
                $("#sra").html(response.users);

            } else {

                $("#sra").html('<p class="text-center text-muted">No users found.</p>');
            }
        }
    });
});

jQuery(document).ready(function () {
    jQuery("time.timeago").timeago();
});
$("#show_not").click(function () {

    $.ajax({
        url: 'assets/php/ajax.php?notread',
        method: 'post',
        dataType: 'json',
        success: function (response) {

            if (response.status) {
                $(".un-count").hide();
            }
        }
    });

});

$(".unblockbtn").click(function () {
    var user_id_v = $(this).data('userId');
    var button = this;
    $(button).attr('disabled', true);
    console.log('clicked');
    $.ajax({
        url: 'assets/php/ajax.php?unblock',
        method: 'post',
        dataType: 'json',
        data: { user_id: user_id_v },
        success: function (response) {
            console.log(response);
            if (response.status) {
                location.reload();
            } else {
                $(button).attr('disabled', false);

                alert('An error occurred. Please try again later.');
            }
        }
    });
});

var chatting_user_id = 0;

$(".chatlist_item").click();

function popchat(user_id) {
    $("#user_chat").html(`<div class="spinner-border text-center" role="status">

  </div>`);

    $("#chatter_username").text('Loading...');
    $("#chatter_name").text('');
    $("#chatter_pic").attr('src', 'assets/images/profile/default_profile.jpg');
    chatting_user_id = user_id;
    $("#sendmsg").attr('data-user-id', user_id);
}

$("#sendmsg").click(function () {
    var user_id = chatting_user_id;
    var msg = $("#msginput").val();
    console.log(user_id);
    if (!msg) return;

    $("#sendmsg").attr("disabled", true);
    $("#msginput").attr("disabled", true);
    $.ajax({
        url: 'assets/php/ajax.php?sendmessage',
        method: 'post',
        dataType: 'json',
        data: { user_id: user_id, msg: msg },
        success: function (response) {
            if (response.status) {
                $("#sendmsg").attr("disabled", false);
                $("#msginput").attr("disabled", false);
                $("#msginput").val('');
            } else {
                alert('An error occurred while sending your message. Please try again later.');
            }
        }
    });

});

function synmsg() {

    $.ajax({
        url: 'assets/php/ajax.php?getmessages',
        method: 'post',
        dataType: 'json',
        data: { chatter_id: chatting_user_id },
        success: function (response) {
            console.log(response);
            $("#chatlist").html(response.chatlist);
            if (response.newmsgcount == 0) {
                $("#msgcounter").hide();
            } else {
                $("#msgcounter").show();
                $("#msgcounter").html("<small>" + response.newmsgcount + "</small>");
            }
            if (response.blocked) {
                $("#msgsender").hide();
                $("#blerror").show();
            } else {
                $("#msgsender").show();
                $("#blerror").hide();
            }

            if (chatting_user_id != 0) {
                $("#user_chat").html(response.chat.msgs);

                $("#chatter_username").text(response.chat.userdata.username);
                $("#cplink").attr('href', '?u=' + response.chat.userdata.username);

                $("#chatter_name").text(response.chat.userdata.first_name + ' ' + response.chat.userdata.last_name);
                $("#chatter_pic").attr('src', 'assets/images/profile/' + response.chat.userdata.profile_pic);
            }
        }
    });
}

synmsg();

setInterval(() => {
    synmsg();
}, 1000);