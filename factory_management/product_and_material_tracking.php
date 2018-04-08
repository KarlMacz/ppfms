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
            <div class="navbar-brand"><span class="fas fa-thumbtack fa-fw"></span> Product and Material Tracking</div>
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
        <div class="col-sm-3 col-sm-offset-9">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">Show</span>
                    <select class="filter-table form-control">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <span class="input-group-addon">Entries</span>
                </div>
            </div>
        </div>
    </div>
    <table id="batches-table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="15%">Batch Number</th>
                <th>Name</th>
                <th width="15%">Quantity</th>
                <th width="15%">In</th>
                <th width="15%">Out</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <nav class="text-right">
        <ul class="pagination no-margin"></ul>
    </nav>
</div>
<?php
    include_once('../layouts/factory_management_end.php');
    include_once('../partials/modals.php');
?>
<script src="../js/custom/factory_management/product_and_material_tracking.js"></script>
<?php
    include_once('../layouts/footer.php');
?>
