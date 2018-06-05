<?php
session_start();
require_once "database/loggedinUser.php";
require "database/config.php";
$bericht = '';
$getid = $_SESSION['id'];
$message = '';

// pak de gebruiker gegevens op.
$pakquery = "select * from user WHERE id ='$getid' ";

//Krijg de huidige gebruiker gegevens, laat error zien als dat voorkomt.
if (!($resultaat = mysqli_query($db, $pakquery))) {
    $message = "Error," . mysqli_error($db);
} else {
    while ($row = mysqli_fetch_assoc($resultaat)) {

        $username = $row['username'];
        $password = $row['password'];
        $id = $row['id'];
    }
}

// Get the user tasks die actief is.
$taskid = $_GET['task_id'];
$tasks = [];
$tasksquery = "SELECT * FROM task WHERE user_id = '$getid' AND idtask ='$taskid'";

// pak de resultaten vanuit database
if ($gettasksquery = mysqli_query($db, $tasksquery)) {
    while ($taskrow = mysqli_fetch_array($gettasksquery)) {

        $tasks[] = $taskrow;
    }
}

// delete task
if (isset($_POST['verwijdertask'])) {

    $deleteid = $_POST['deleteid'];

    $verwijderquery = "DELETE FROM task WHERE idtask='$deleteid' ";
    mysqli_query($db, $verwijderquery);
    header('location: dashboard.php');
}

// complete task
if (isset($_POST['completetask'])) {

    $finishtask = $_POST['finishtask'];

    $completequery = "UPDATE user, task
    SET task.active = 0,
    user.points = user.points +5
    WHERE user.id = task.user_id
    AND task.idtask ='$finishtask'";

    mysqli_query($db, $completequery);
    header('location: dashboard.php');
}

?>
<!DOCTYPE html>
<html>
<head lang="en">
    <?php include 'header.php';?>
</head>
<body>
    <!-- Always shows a header, even in smaller screens. -->
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
        <header class="mdl-layout__header">
            <div class="mdl-layout__header-row">
                <!-- Title -->
                <span class="mdl-layout-title">Task</span>

                <!-- Add spacer, to align navigation to the right -->
                <div class="mdl-layout-spacer"></div>

                <!-- Navigation. We hide it in small screens. -->
                <nav class="mdl-navigation">

                </nav>
            </div>
        </header>
        <div class="mdl-layout__drawer">
            <span class="mdl-layout-title">Menu</span>

            <nav class="mdl-navigation">

                <a class="mdl-navigation__link" href="dashboard.php">
                <i class="material-icons icon">dashboard</i>
                &nbsp;&nbsp;&nbsp; Dashboard
                </a>

                <a class="mdl-navigation__link" href="achievements.php">
                    <i class="material-icons icon">wb_sunny</i>
                    &nbsp;&nbsp;&nbsp; Achievements
                </a>

                <a class="mdl-navigation__link" href="leaderboard.php">
                    <i class="material-icons icon">filter_vintage</i>
                    &nbsp;&nbsp;&nbsp; Leaderboard
                </a>

                <a class="mdl-navigation__link" href="group.php">
                    <i class="material-icons icon">person_add</i>
                    &nbsp;&nbsp;&nbsp; Groups
                </a>

                <a class="mdl-navigation__link" href="logout.php">
                    <i class="material-icons icon">keyboard_tab</i>
                    &nbsp;&nbsp;&nbsp; Logout
                </a>

            </nav>

        </div>

        <main class="mdl-layout__content">
            <div class="insideapplication">

                <ul class="demo-list-item mdl-list task">
                    <?php if (!empty($tasks)) {?>
                        <?php foreach ($tasks as $taskrow) {?>
                            <h4>Task</h4>
                            <li class="mdl-list__item">
                                <span class="mdl-list__item-primary-content">
                                    <?=$taskrow['taskname'];?>
                                </span>
                            </li>
                            <h4>Date</h4>
                            <li class="mdl-list__item">
                                <span class="mdl-list__item-primary-content">
                                    <?=$taskrow['date'];?>
                                </span>
                            </li>
                            <h4>Reward Points for finishing</h4>
                            <li class="mdl-list__item">
                                <!-- Contact Chip -->
                            <span class="mdl-chip mdl-chip--contact">
                                <span class="mdl-chip__contact mdl-color--teal mdl-color-text--white"><?=$taskrow['points'];?></span>
                                <span class="mdl-chip__text">points</span>
                            </span>
                            </li>

                        <?php }?>
                    <?php }?>
                </ul>

                <form method="post" action="task.php">
                <input type="hidden" value="<?=$taskrow['idtask'];?>" name='deleteid'/>
                    <button type="submit" name="verwijdertask" class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent taskdeletebtn">
                        Delete
                    </button>
                </form>
            </div>
        </main>

        <button id="demo-menu-top-left" class="mdl-button mdl-js-button mdl-button--icon">
            <form method="post" action="task.php" id="demo-menu-top-left" class="mdl-button mdl-js-button mdl-button--icon">
                <input type="hidden" value="<?=$taskrow['idtask'];?>" name='finishtask'/>
                <button type="submit" name="completetask" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
                    Complete task
                </button>
            </form>
        </button>

    </div>

</body>
</html>