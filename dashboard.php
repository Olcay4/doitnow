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
$datums = [];
$datumquery = "select * from task WHERE user_id ='$getid' AND active = '1' ORDER BY date";

// pak de resultaten vanuit database
if ($pakdatumresultaat = mysqli_query($db, $datumquery)) {
    while ($row = mysqli_fetch_assoc($pakdatumresultaat)) {
        $datums[] = $row;
    }
}

// Task opslaan
if (isset($_POST['opslaantask'])) {
    $task = $_POST['taskname'];
    $datecheck = $_POST['date'];

    if (empty($task) || empty($datecheck)) {
        $message = "* Fill all fields";

    } else {
        $date = $_POST['date'];
        $date = date('y.m.d');

        $addnewtask = "INSERT INTO task (date, taskname, active, points, user_id)
        VALUES ('$date', '$task', 1, 5, '$getid');";
        mysqli_query($db, $addnewtask);
        header('location: dashboard.php');
        mysqli_close($db);
    }
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
            <span class="mdl-layout-title">Dashboard</span>
            <!-- Add spacer, to align navigation to the right -->
            <div class="mdl-layout-spacer"></div>
            </div>
        </header>
        <div class="mdl-layout__drawer">
            <span class="mdl-layout-title">Menu</span>

            <nav class="mdl-navigation">

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

                <h4 class="insideTitle">Tasks</h4>

                <ul class="demo-list-item mdl-list dashboard">
                    <?php if (!empty($datums)) {?>
                        <?php foreach ($datums as $row) {?>

                            <li class="mdl-list__item">
                                <span class="mdl-list__item-primary-content">
                                    <?=$row['taskname'];?><br><br>
                                    <?=$row['date'];?>

                                </span>
                                <form method="post" action="dashboard.php">
                                    <input type="hidden" value="<?=$row['idtask'];?>" name='taskid'/>
                                    <!-- <button type="submit" name="sumittask">go</button> -->
                                    <a name="sumittask" class="mdl-list__item-secondary-action" href='task.php?task_id=<?=$row['idtask'];?>'>
                                        <i class="material-icons">forward</i>
                                    </a>
                                </form>
                            </li>

                        <?php }?>
                    <?php }?>
                </ul>

                <hr>

                <!-- Simple Textfield -->
                <form class="taskcreator" action="dashboard.php" method="post">
                    <h6>Create a new task</h6>
                    <div class="taskcreator__list">
                        <div class="mdl-textfield mdl-js-textfield">
                            <input name="taskname" class="mdl-textfield__input" type="text" id="sample1">
                            <label class="mdl-textfield__label" for="sample1">New task name</label>

                        </div>
                        <div>
                            <input name="date" type="date" name="bday">
                        </div>
                        <br>
                        <button name="opslaantask" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
                            Create task
                        </button>
                        <br><?=$message?>
                    </div>
                </form>

            </div>
        </main>
    </div>
</body>
</html>