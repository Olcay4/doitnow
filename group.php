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

// Get users
$users = [];
$userquery = "select * from groups";

// pak de resultaten vanuit database
if ($getuserresultaat = mysqli_query($db, $userquery)) {
    while ($row = mysqli_fetch_assoc($getuserresultaat)) {
        $users[] = $row;
    }
}

// Get the data of the group you've joined in.
$usergroup = [];
$usergroupquery = "SELECT *
FROM user_has_groups
INNER JOIN user ON user.id = user_has_groups.user_id
INNER JOIN groups ON groups.idgroups = user_has_groups.groups_idgroups
WHERE user.id ='$getid'
";

// pak de resultaten vanuit database
if ($getusergroupresultaat = mysqli_query($db, $usergroupquery)) {
    while ($row = mysqli_fetch_assoc($getusergroupresultaat)) {
        $usergroup[] = $row;
    }
}

// Groep opslaan
if (isset($_POST['opslaantask'])) {
    $group = $_POST['textgroupname'];
    $groupinfo = $_POST['textgroupinfo'];

    if (empty($group) || empty($groupinfo)) {
        $message = "Give a group name";

    } else {

        $addnewgroup = "INSERT INTO groups (groupname, groupinfo)
        VALUES ('$group', '$groupinfo');";
        mysqli_query($db, $addnewgroup);
        header('location: group.php');
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

        <header class="mdl-layout__header mdl-layout__header--scroll">

            <div class="mdl-layout__header-row">

                <!-- Title -->
                <span class="mdl-layout-title">Groups</span>
                <!-- Add spacer, to align navigation to the right -->
                <div class="mdl-layout-spacer"></div>
                <!-- Navigation -->
                <a name="sumittask" class="mdl-list__item-secondary-action" id="show-dialog">
                    <i class="material-icons">add_circle</i>
                </a>

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

            <a class="mdl-navigation__link" href="logout.php">
                <i class="material-icons icon">keyboard_tab</i>
                &nbsp;&nbsp;&nbsp; Logout
            </a>

        </nav>
        </div>
        <main class="mdl-layout__content">
            <div class="insideapplication">

                <h6 class="insideTitle">Created groups are shown here.</h6>

                <div class="demo-list-action mdl-list">

                    <?php if (!empty($users)) {?>
                        <?php foreach ($users as $row) {?>
                            <a href='singlegroup.php?id_groups=<?=$row['idgroups'];?>'>
                                <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored btninside">
                                    <?=$row['groupname'];?>
                                </button>
                            </a>
                        <?php }?>
                    <?php }?>

                </div>

                <hr>
                <h6 class="insideTitle">Groups you've joined.</h6>

                <?php if (!empty($usergroup)) {?>
                        <?php foreach ($usergroup as $row) {?>
                            <a href='singlegroup.php?id_groups=<?=$row['idgroups'];?>'>
                                <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored btninside">
                                    <?=$row['groupname'];?>
                                </button>
                            </a>
                        <?php }?>
                    <?php }?>

                <dialog class="mdl-dialog">
                    <h4 class="mdl-dialog__title">Create group</h4>
                    <div class="mdl-dialog__content">
                    <p>
                        Create a custom group for your friends and yourself.
                    </p>

                    <form class="taskcreator" action="group.php" method="post">

                        <div class="mdl-textfield mdl-js-textfield">
                            <input name="textgroupname" class="mdl-textfield__input" type="text" id="sample1">
                            <label class="mdl-textfield__label" for="sample1">New Group name</label>
                        </div>

                        <div class="mdl-textfield mdl-js-textfield">
                            <input name="textgroupinfo" class="mdl-textfield__input" type="text" id="sample2">
                            <label class="mdl-textfield__label" for="sample2">Give group info</label>
                        </div>
                        </div>

                        <div class="mdl-dialog__actions">
                            <button name="opslaantask" class="mdl-button">Create a group</button>
                            <button type="button" class="mdl-button close">Cancel</button>
                        </div>
                    </form>
                </dialog>
                <script>
                    var dialog = document.querySelector('dialog');
                    var showDialogButton = document.querySelector('#show-dialog');
                    if (! dialog.showModal) {
                    dialogPolyfill.registerDialog(dialog);
                    }
                    showDialogButton.addEventListener('click', function() {
                    dialog.showModal();
                    });
                    dialog.querySelector('.close').addEventListener('click', function() {
                    dialog.close();
                    });
                </script>

            </div>
        </main>
    </div>
</body>
</html>