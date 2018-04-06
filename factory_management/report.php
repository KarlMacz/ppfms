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
            <div class="navbar-brand"><span class="fas fa-flag fa-fw"></span> Report</div>
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
            <h3 class="page-header">Generate Report</h3>
            <div class="form-group">
                <label for="report-input">Report Type:</label>
                <select name="" id="report-input" class="form-control">
                    <option value="" selected disabled>Select an option...</option>
                    <option value="../backend/pdf/generate_audit_trail_report.php">Audit Trail</option>
                    <option value="../backend/pdf/generate_inventory_report.php">Inventory</option>
                </select>
            </div>
        </div>
        <div class="col-sm-8">
            <div style="height: 550px;">
                <iframe src="../partials/report_placeholder.php" frameborder="0" id="report-frame" class="page-framing full-height"></iframe>
            </div>
        </div>
    </div>
</div>
<?php
    include_once('../layouts/factory_management_end.php');
    include_once('../partials/modals.php');
?>
<script src="../js/custom/factory_management/report.js"></script>
<?php
    include_once('../layouts/footer.php');
?>
