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
            <span class="mdl-layout-title">Achievements</span>
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

                <div class="demo-card-square mdl-card mdl-shadow--2dp">
                <div class="mdl-card__title mdl-card--expand">
                    <svg fill="currentColor" width="200px" height="200px" viewBox="0 0 1 1" class="demo-chart mdl-cell mdl-cell--4-col mdl-cell--3-col-desktop pointsstyle">
                        <use xlink:href="#piechart" mask="url(#piemask)" />
                        <text x="0.5" y="0.5" font-family="Roboto" font-size="0.5" fill="#ffffff" text-anchor="middle" dy="0.1"><?=$points?><tspan font-size="0.2" dy="-0.07">points</tspan></text>
                    </svg>
                </div>
                <div class="mdl-card__supporting-text">
                    You can see your archived points via this tab. You can always share your score to show how productive you are.
                </div>
                    <div class="mdl-card__actions mdl-card--border">
                        <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="https://twitter.com/intent/tweet?text=I've%20earned%20'<?=$points?>'%20points%20on%20Do%20it%20now!,%20you%20can%20also%20join%20the%20app">
                        Share your points on twitter
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>