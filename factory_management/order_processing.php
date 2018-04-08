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
            <div class="navbar-brand"><span class="fas fa-th-list fa-fw"></span> Order Processing</div>
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
    <table id="orders-table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="15%">Tracking Number</th>
                <th>Product(s)</th>
                <th>Date & Time Ordered</th>
                <th width="10%">Action(s)</th>
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
<div id="dispatch-order-modal" class="modal fade" data-id="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Order Registry</h4>
            </div>
            <div class="modal-body">
                <p>Order Registry will mark this order as <strong>Dispatched</strong>. Dispatched orders will be moved to <a href="dispatch_tracking.php">Dispatch Tracking</a>.</p>
                <p>Are you sure you want to continue?</p>
            </div>
            <div class="modal-footer">
                <div class="text-right">
                    <button type="button" class="negative-button btn btn-default"><span class="fas fa-times fa-fw"></span> No</button>
                    <button type="button" class="positive-button btn btn-success"><span class="fas fa-check fa-fw"></span> Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="cancel-order-modal" class="modal fade" data-id="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cancel Order</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this order?</p>
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
<script src="../js/custom/factory_management/order_processing.js"></script>
<?php
    include_once('../layouts/footer.php');
?>
