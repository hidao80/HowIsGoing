'use strict';
/* global translation */
const INSTALL_PATH = '';

/**
 * 
 */
function saveTasks() {
    const user_id = localStorage['user_id'];

    if (!user_id) {
        alert(translation['Please select user first']);
        return;
    }

    $.ajax({
        url: INSTALL_PATH + 'api/v1/save/index.php',
        type: 'post',
        data: JSON.stringify({
            user_id: user_id,
            todo: $('#floatingTextarea2').val()
        }),
        success: function (data) {
            toastr.success(translation['Status saved']);
            setTimeout(function () {
                location.reload()
            }, 1000);
        },
        error: function (data) {
            toastr.error(data.status + ": " + translation['Status saved failed']);
        },
        complete: function (data) {
            console.log('status code: ' + data.status);
        }
    });
}

function turnOnLamps() {
    $('.card').each(function () {
        var resultLamps = ['', '', '', ''];
        $(this).find('tr').each(function () {
            switch ($(this).find('option:selected').val()) {
                case '0':
                    resultLamps[0] += "<span class='text-dark'>●</span>";
                    break;
                case '1':
                    resultLamps[1] += "<span class='text-danger'>●</span>";
                    break;
                case '2':
                    resultLamps[2] += "<span class='text-warning'>●</span>";
                    break;
                case '3':
                    resultLamps[3] += "<span class='text-success'>●</span>";
                    break;
            }
        });

        var lampsTags = "";
        resultLamps.reverse().forEach(function (element) {
            lampsTags += element;
        });
        $(this).find('.status_lamps').html(lampsTags);
    });

}

/**
 * 
 * @returns 
 */
function saveStatus() {
    const user_id = localStorage['user_id'];

    if (!user_id) {
        alert(translation['Please select user first']);
        return;
    }

    var tasks = {
        user_id: user_id,
        content: []
    };

    $('#card-' + user_id).find('tr').each(function () {
        tasks.content.push({
            id: $(this).data('id'),
            status: $(this).find('option:selected').val(),
            comment: $(this).find('textarea').val()
        });
    });
    tasks.content.splice(0, 1);

    $.ajax({
        url: INSTALL_PATH + 'api/v1/update/index.php',
        type: 'post',
        data: JSON.stringify({
            user_id: user_id,
            tasks: tasks
        }),
        success: function (data) {
            toastr.success(translation['Status saved']);
            turnOnLamps();
        },
        error: function (data) {
            toastr.error(data.status + ": " + translation['Status saved failed']);
        },
        complete: function (data) {
            console.log('status code: ' + data.status);
        }
    });
}

/**
 * 
 */
function getUserIdForLoocalStorage() {
    localStorage['user_id'] = $("#selectUserDialog option:selected").val();
    $("#selected_user_name").text($("#selectUserDialog option:selected").text());
    $(this).dialog("close");
    changeActiveUser();
    showMyStatus();
}

/**
 * 
 */
function showMyStatus() {
    $(".card").addClass('d-none');
    $("#card-" + localStorage.getItem('user_id')).removeClass('d-none');
    $('#showMyStatus').addClass('active');
    $('#showEveryoneStatus').removeClass('active');
}

/**
 * 
 */
function showEveryoneStatus() {
    $(".card").removeClass('d-none');
    $('#showMyStatus').removeClass('active');
    $('#showEveryoneStatus').addClass('active');
}

/**
 * 
 */
function changeActiveUser() {
    // Other user’s Prevents the task from being edited.
    $('.card').each(function () {
        if ($(this).data('user-id') != localStorage['user_id']) {
            $(this).find('button').attr('disabled', true);
            $(this).find('textarea').attr('disabled', true);
        } else {
            $(this).find('button').removeAttr('disabled');
            $(this).find('textarea').removeAttr('disabled');
        }
    });
}

// Initialize
$(function () {
    // If the user is not selected, the user selection 
    // dialog is automatically opened.
    var user_id = localStorage['user_id'];
    if (!user_id) {
        $("#selectUserDialog").modal('show');
    } else {
        $("#selected_user_name").text($(`option[value='${localStorage['user_id']}']`).text());

        // Edit and save automatically after more than 3 seconds.
        var timer = null;
        $('#card-' + user_id).find('.dropdown').on('click', function () {
            clearTimeout(timer);
            timer = setTimeout(saveStatus, 3000);
        });
        $('#card-' + user_id).find('textarea').on('keydown', function () {
            clearTimeout(timer);
            timer = setTimeout(saveStatus, 3000);
        });

        changeActiveUser();
    }
});
