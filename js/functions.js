'use strict';
/* global translation */
const INSTALL_PATH = '';

/**
 * Register the tasks you entered in the "Enter today's todo" dialog.
 */
function saveTasks() {
    const user_id = localStorage['user_id'];

    // If the user is not selected, it will alert and abort.
    if (!user_id) {
        alert(translation['Please select user first']);
        return;
    }

    // Access the task registration API.
    $.ajax({
        url: INSTALL_PATH + 'api/v1/task/save/index.php',
        type: 'post',
        data: JSON.stringify({
            user_id: user_id,
            todo: $('#floatingTextarea2').val()
        }),
        success: function (data) {
            // Reload the page one second after displaying the toast.
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

/**
 * Access the API to delete the target task.
 */
function deleteTask() {
    const user_id = localStorage['user_id'];
    const task_id = parseInt($('#delete_target_task_id').data('id'));
    console.log(task_id)

    // If the user is not selected, it will alert and abort.
    if (!user_id) {
        alert(translation['Please select user first']);
        return;
    }

    if (!task_id) {
        console.log('task_id is not defined');
        return;
    }

    // Access the task deletion API.
    $.ajax({
        url: INSTALL_PATH + 'api/v1/task/delete/index.php',
        type: 'post',
        data: JSON.stringify({
            user_id: user_id,
            task_id: task_id,
        }),
        success: function (data) {
            // Reload the page one second after displaying the toast.
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

/**
 * Draws status lights for all users.
 */
function turnOnLamps() {
    // For all users
    $('.card').each(function () {
        var resultLamps = ['', '', '', ''];

        // View all tasks for the user
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

        // Sort by highest status value
        var lampsTags = "";
        resultLamps.reverse().forEach(function (element) {
            lampsTags += element;
        });
        $(this).find('.status_lamps').html(lampsTags);
    });

}

/**
 * Records the status changes of a task.
 */
function saveStatus() {
    const user_id = localStorage['user_id'];

    // If the user is not selected, it will alert and abort.
    if (!user_id) {
        alert(translation['Please select user first']);
        return;
    }

    var tasks = {
        user_id: user_id,
        content: []
    };

    // Access the status update API.
    $('#card-' + user_id).find('tr').each(function () {
        tasks.content.push({
            id: $(this).data('id'),
            status: $(this).find('option:selected').val(),
            comment: $(this).find('textarea').val()
        });
    });
    tasks.content.splice(0, 1);

    $.ajax({
        url: INSTALL_PATH + 'api/v1/task/update/index.php',
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
 * Saves the user information from the user selection dialog to local storage.
 */
function getUserIdForLoocalStorage() {
    localStorage['user_id'] = $("#selectUserDialog option:selected").val();
    $("#selected_user_name").text($("#selectUserDialog option:selected").text());
    $(this).dialog("close");
    changeActiveUser();
    showMyStatus();
}

/**
 * Switches to a mode where only the tasks of the currently selected user are displayed.
 */
function showMyStatus() {
    $(".card").addClass('d-none');
    $("#card-" + localStorage.getItem('user_id')).removeClass('d-none');
    $('#showMyStatus').addClass('active');
    $('#showEveryoneStatus').removeClass('active');
}

/**
 * Switches the mode to show all users' tasks.
 */
function showEveryoneStatus() {
    $(".card").removeClass('d-none');
    $('#showMyStatus').removeClass('active');
    $('#showEveryoneStatus').addClass('active');
}

/**
 * Switch the current user.
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

    $('.btn-task-delete').on('click', function () {
        const $element = $(this).parent().parent();
        console.log($element.data('id'));
        const task_id = $element.data('id');
        const task_name = $element.find('label').text();

        $('#delete_target_task_id').data('id', task_id);
        $('#delete_target_task_id').text(translation['Task name is'] + ': ' + task_name);
    });
});
