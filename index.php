<?php
declare(strict_types=1);

require_once(__DIR__ . '/lib/global_variables.php');
require_once(__DIR__ . '/lib/functions.php');

?>
<!DOCTYPE html>
<html lang="<?= $LANG ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= translation('How is going?') ?></title>

    <!-- jQuery 1.12 CSS/JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        referrerpolicy="no-referrer"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css">

    <!-- BS5.1.1 CSS/JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Latest BS-Select compiled and minified CSS/JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

    <!-- toastr.js CSS/JS -->
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <!-- Original CSS/JS -->
    <script src="./js/functions.js"></script>
    <link href="./css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><?= translation('How is going?') ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" id="showMyStatus" onclick="showMyStatus()"><?= translation('My status') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="showEveryoneStatus" onclick="showEveryoneStatus()"><?= translation("Everyone's status") ?></a>
                </li>
                <li class="nav-item mr-auto">
                    <button type="button" class="btn btn-link nav-link" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <?= translation("Enter today's todo") ?>   
                    </button>
                </li>
            </ul>
            <div  class="navbar-nav ml-auto">
                <span class="nav-item" id="selected_user_name"> 
                </span>
            </div>
            <div class="navbar-nav">
                <span class="nav-item">
                    <button type="button" class="btn btn-link nav-link" data-bs-toggle="modal" data-bs-target="#selectUserDialog">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16"><path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/><path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/></svg>
                    </button>
                </span>
            </div>
        </div>
    </div>
</nav>

<main>
    <div class="container">
        <?php
        $users = getUsers();
        foreach ($users as $user) :
            $tasks = getTodayTasks(intval($user['id']));
            ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-3 mr-3 d-none" id="card-<?= $user['id'] ?>" data-user-id="<?= $user['id'] ?>">
                    <div class="card-header">
                        <span class="h5"><?= $user['display_name'] ?></span>
                        &nbsp;&nbsp;
                        <span class="status_lamps"></span>
                    </div>
                    <div class="card-body">
                        <div class="card-text">
                            <table class="table table-hover table-borderless">
                                <thead>
                                    <tr>
                                        <th><?= translation('Status') ?></th>
                                        <th><?= translation('Task notes') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php foreach ($tasks as $index => $task) : ?>
                                    <tr data-id="<?= $task['id'] ?>">
                                        <td>
                                            <select data-show-content="true" class="selectpicker status">
                                            <option <?= $task['status'] == 0 ? 'selected' : '' ?> value="0" data-content="<span class='text-dark'><?= translation('status-0') ?></span>"></option>
                                            <option <?= $task['status'] == 1 ? 'selected' : '' ?> value="1" data-content="<span class='text-danger'><?= translation('status-1') ?></span>"></option>
                                            <option <?= $task['status'] == 2 ? 'selected' : '' ?> value="2" data-content="<span class='text-warning'><?= translation('status-2') ?></span>"></option>
                                            <option <?= $task['status'] == 3 ? 'selected' : '' ?> value="3" data-content="<span class='text-success'><?= translation('status-3') ?></span>"></option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="form-floating">
                                                <textarea class="form-control w-100" style="height: 6rem" id="floatingTextarea-<?= $index ?>"><?= $task['comment'] ?></textarea>
                                                <label for="floatingTextarea-<?= $index ?>"><?= $task['task'] ?></label>
                                            </div>
                                        </td>
                                    </tr>
                            <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>    
</main>

<!-- Today's tasks modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalLabelId"><?= translation("Please enter today's todo.") ?></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= translation("Crose") ?>"></button>
            </div>
            <div class="modal-body">
            <div class="">
                <textarea class="form-control" placeholder="<?= translation("Input example") ?>" id="floatingTextarea2" style="height: 300px"></textarea>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= translation("Cancel") ?></button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="saveTasks()">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- User select modal  -->
<div class="modal fade" id="selectUserDialog" tabindex="-1" aria-labelledby="dialogLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalLabelId2"><?= translation("User select") ?></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?= translation("Crose") ?>"></button>
            </div>
            <div class="modal-body">
            <div class="">
                <label for="user_name"><?= translation('Pleas select your name') ?></label>
                <select id="user_name" class="form-control">
                    <?= createUserOptions() ?>
                </select>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?= translation("Cancel") ?></button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="getUserIdForLoocalStorage()">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    showMyStatus();
    turnOnLamps();
    var translation = <?= file_get_contents(__DIR__ . "/lang/$LANG.json") ?>;
</script>
</body>
</html>
