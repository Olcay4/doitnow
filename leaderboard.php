<?php
session_start();
require_once "database/loggedinUser.php";
require "database/config.php";
$bericht = '';
$getid = $_SESSION['id'];

// pak de gebruiker gegevens op.
$pakquery = "select * from user WHERE id ='$getid' ";

//Krijg de huidige gebruiker gegevens, laat fout zien als dat voorkomt.
if (!($resultaat = mysqli_query($db, $pakquery))) {
    $message = "Error," . mysqli_error($db);
} else {
    while ($row = mysqli_fetch_assoc($resultaat)) {

        $username = $row['username'];
        $password = $row['password'];
        $points = $row['points'];
        $id = $row['id'];
    }
}

// Get users
$users = [];
$userquery = "select * from user ORDER BY points DESC";

// pak de resultaten vanuit database
if ($getuserresultaat = mysqli_query($db, $userquery)) {
    while ($row = mysqli_fetch_assoc($getuserresultaat)) {
        $users[] = $row;
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
            <span class="mdl-layout-title">Leaderboard</span>
            <!-- Add spacer, to align navigation to the right -->
            <div class="mdl-layout-spacer"></div>
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

                <h6 class="insideTitle">Leaderboard page, the users with the most participation points will be shown here.</h6> 

                <div class="demo-list-action mdl-list">

                    <?php if (!empty($users)) {?>
                        <?php foreach ($users as $row) {?>

                            <div class="mdl-list__item">
                                <input type="hidden" value="<?=$row['id'];?>" name='taskid'/>
                                <span class="mdl-list__item-primary-content">
                                    <span><?=$row['username'];?></span>
                                    
                                </span>
                                    <span><?=$row['points'];?></span>
                            </div>

                        <?php }?>
                    <?php }?>

                </div>

            </div>
        </main>
    </div>
</body>
</html>