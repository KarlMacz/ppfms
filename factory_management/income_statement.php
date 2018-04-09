<?php
    session_start();

    require_once('../backend/database.php');
    require_once('../backend/functions.php');

    include_once('../layouts/authorized_header.php');
    include_once('../layouts/factory_management_start.php');
?>
<nav class="navbar navbar-default navbar-static-top no-margin">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="navbar-brand"><span class="fas fa-dollar-sign fa-fw"></span> Income Statement</div>
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
<div id="page-wrapper-content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="page-header">Generate Income Statement</h3>
            <div class="form-group">
                <label for="month-input">Month:</label>
                <select name="" id="month-input" class="form-control" required autofocus>
                    <option value="" selected disabled>Select an option...</option>
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
            <div class="form-group">
                <label for="year-input">Year:</label>
                <input type="number" id="year-input" class="form-control" min="2000" max="<?php echo date('Y'); ?>" placeholder="Year" value="<?php echo date('Y'); ?>" required>
            </div>
        </div>
        <div class="col-sm-8">
            <div style="height: 550px;">
                <iframe src="../partials/report_placeholder.php" frameborder="0" id="accounting-frame" class="page-framing full-height"></iframe>
            </div>
        </div>
    </div>
</div>
<?php
    include_once('../layouts/factory_management_end.php');
    include_once('../partials/modals.php');
?>
<script src="../js/custom/factory_management/income_statement.js"></script>
<?php
    include_once('../layouts/footer.php');
?>
