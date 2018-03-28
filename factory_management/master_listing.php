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
            <div class="navbar-brand"><span class="fas fa-users fa-fw"></span> Master Listing</div>
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
        <div class="col-sm-6">
            <h3 class="page-header">Buyers</h3>
            <div class="row">
                <div class="col-sm-6 col-sm-offset-6">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">Show</span>
                            <select class="buyers-filter-table form-control">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                            <span class="input-group-addon">Entries</span>
                        </div>
                    </div>
                </div>
            </div>
            <table id="buyers-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th width="20%">Action(s)</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <nav class="text-right">
                <ul class="buyers-pagination pagination no-margin"></ul>
            </nav>
        </div>
        <div class="col-sm-6">
            <h3 class="page-header">Suppliers</h3>
            <div class="row">
                <div class="col-sm-6 col-sm-offset-6">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">Show</span>
                            <select class="suppliers-filter-table form-control">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                            <span class="input-group-addon">Entries</span>
                        </div>
                    </div>
                </div>
            </div>
            <table id="suppliers-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th width="20%">Action(s)</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <nav class="text-right">
                <ul class="suppliers-pagination pagination no-margin"></ul>
            </nav>
        </div>
    </div>
</div>
<?php
    include_once('../layouts/factory_management_end.php');
    include_once('../partials/modals.php');
?>
<div id="view-buyer-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">View Buyer's Information</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<div id="delete-buyer-modal" class="modal fade" data-id="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Buyer's Information</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this buyer's information?</p>
            </div>
            <div class="modal-footer">
                <div class="text-right">
                    <button type="button" class="negative-button btn btn-default"><span class="fas fa-times fa-fw"></span> No</button>
                    <button type="button" class="positive-button btn btn-danger"><span class="fas fa-check fa-fw"></span> Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="view-supplier-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">View Supplier's Information</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<div id="delete-supplier-modal" class="modal fade" data-id="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Supplier's Information</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this supplier's information?</p>
            </div>
            <div class="modal-footer">
                <div class="text-right">
                    <button type="button" class="negative-button btn btn-default"><span class="fas fa-times fa-fw"></span> No</button>
                    <button type="button" class="positive-button btn btn-danger"><span class="fas fa-check fa-fw"></span> Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="../js/custom/factory_management/master_listing.js"></script>
<?php
    include_once('../layouts/footer.php');
?>
