<?php
session_start();
require_once "database/loggedinUser.php";
require "database/config.php";
$bericht = '';
$getid = $_SESSION['id'];
$message = '';

// pak de gebruiker gegevens op.
$pakquery = "select * from user WHERE id ='$getid' ";

//Krijg de huidige gebruiker gegevens, laat fout zien als dat voorkomt.
if (!($resultaat = mysqli_query($db, $pakquery))) {
    $message = "Error," . mysqli_error($db);
} else {
    while ($row = mysqli_fetch_assoc($resultaat)) {

        $username = $row['username'];
        $password = $row['password'];
        $id = $row['id'];
    }
}

// Get singlegroup id.
$groupid = $_GET['id_groups'];
$groups = [];
$groupquery = "SELECT * FROM groups WHERE idgroups = '$groupid'";

// pak de resultaten vanuit database
if ($getgroupquery = mysqli_query($db, $groupquery)) {
    while ($grouprow = mysqli_fetch_array($getgroupquery)) {

        $groups[] = $grouprow;
    }
}

$usergroups = [];
$groupusersquery = " SELECT * FROM user_has_groups
INNER JOIN user ON user.id = user_has_groups.user_id
INNER JOIN groups ON groups.idgroups = user_has_groups.groups_idgroups
WHERE user_has_groups.groups_idgroups = '$groupid' ORDER BY points DESC";

// Get username results who are connected with the group
if ($getusergroupquery = mysqli_query($db, $groupusersquery)) {
    while ($usergroupsrow = mysqli_fetch_array($getusergroupquery)) {

        $usergroups[] = $usergroupsrow;
    }
}

// add the current user to the group + validatiecontrole
if (isset($_POST['addusertogroup'])) {
    $currentgroup = $_GET['id_groups'];
    $currentuser = $getid;

    $checkusernamequery = " SELECT * FROM user_has_groups
    INNER JOIN user ON user.id = user_has_groups.user_id
    INNER JOIN groups ON groups.idgroups = user_has_groups.groups_idgroups
    WHERE user.id = $getid AND groups.idgroups = $currentgroup
    ";
    $checkit = mysqli_query($db, $checkusernamequery);
    $checkcombination = mysqli_fetch_array($checkit);

    // Check if you've joined this group with this current logged in user.
    if ($checkcombination) {
        $message = "You've already joined this group.";

    } else {

        $query = "INSERT INTO user_has_groups ( user_id, groups_idgroups)
          VALUES ( '$currentuser','$currentgroup');";
        mysqli_query($db, $query);
        mysqli_close($db);
        header('location: singlegroup.php?id_groups=' . $currentgroup . '');
    }
}

if (isset($_POST['deleteusertogroup'])) {
    $currentuser = $getid;
    $currentgroup = $_GET['id_groups'];
    
    $query = "DELETE FROM user_has_groups WHERE user_id = '$currentuser' AND groups_idgroups = '$currentgroup'";
    mysqli_query($db, $query);
    mysqli_close($db);
    header('location: singlegroup.php?id_groups=' . $currentgroup . '');
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

        <header class="mdl-layout__header mdl-layout__header--scroll">
            <div class="mdl-layout__header-row">
            <!-- Title -->
            <span class="mdl-layout-title">Group</span>
            <!-- Add spacer, to align navigation to the right -->
            <div class="mdl-layout-spacer"></div>
            <!-- Navigation -->

            <form method="post">
                <button name="addusertogroup" class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored">
                    <i class="material-icons">add_circle_outline</i>
                </button>
            </form>

            <form method="post">
                <button name="deleteusertogroup" class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored">
                    <i class="material-icons">remove_circle_outline</i>
                </button>
            </form>

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

                <?php if (!empty($groups)) {?>
                    <?php foreach ($groups as $grouprow) {?>
                        <h4 class="insideTitle">Group: <?=$grouprow['groupname'];?></h4>
                        <h6 class="insideTitle">groupinfo: <?=$grouprow['groupinfo'];?></h6>
                    <?php }?>
                <?php }?>

                <hr>

                <h6 class="insideTitle">Members who've joined this group are: </h6>

                <ul class="demo-list-item mdl-list">

                    <?php if (!empty($usergroups)) {?>
                        <?php foreach ($usergroups as $usergroupsrow) {?>

                            <li class="mdl-list__item mdl-list__item--two-line">
                                <span class="mdl-list__item-primary-content">
                                <i class="material-icons mdl-list__item-avatar">person</i>

                                    <span><?=$usergroupsrow['username'];?></span>
                                    <span class="mdl-list__item-sub-title"><?=$usergroupsrow['points'];?> points</span>
                                </span>
                                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="https://twitter.com/intent/tweet?text=Hey%20<?=$usergroupsrow['username'];?>%20keep%20up%20with%20our%20<?=$usergroupsrow['groupname'];?>%20group,%20you%20are%20still%20at%20<?=$usergroupsrow['points'];?>%20points.">
                                    Share
                                </a>  
                            </li>

                        <?php }?>
                    <?php }?>
                </ul>
                <h6 class="insideTitle"><?=$message?></h6>
            </div>
        </main>
    </div>
</body>
</html>