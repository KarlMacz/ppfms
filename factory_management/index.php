<?php
    session_start();

    require_once('../backend/database.php');
    require_once('../backend/functions.php');

    include_once('../layouts/authorized_header.php');
?>
<div id="wrapper">
    <div class="sidebar" style="margin-top: 0;">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li class="content">
                    <img src="../img/logo.png">
                </li>
                <li><a href="index.php"><span class="fas fa-tachometer-alt fa-fw"></span> Dashboard</a></li>
            </ul>
        </div>
    </div>
    <div id="page-wrapper">
        <nav class="navbar navbar-default navbar-static-top no-margin">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="navbar-brand"><span class="fas fa-tachometer-alt fa-fw"></span> Dashboard</div>
                </div>
                <div class="collapse navbar-collapse" id="navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['full_name']; ?> <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                            <li><a href="profile.php">Profile</a></li>
                            <li class="divider"></li>
                            <li><a href="../logout.php">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div id="page-wrapper-content"></div>
    </div>
</div>
<?php
    include_once('../partials/modals.php');
    include_once('../layouts/footer.php');
?>
