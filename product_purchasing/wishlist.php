<?php
    session_start();

    require_once('../backend/database.php');
    require_once('../backend/functions.php');

    include_once('../layouts/authorized_product_purchasing_header.php');
    include_once('../layouts/product_purchasing_start.php');
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
            <div class="navbar-brand"><span class="fas fa-heart fa-fw"></span> Wishlist</div>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['full_name']; ?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="../index.php">Homepage</a></li>
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
    <table id="wishlist-table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="15%">Product Code</th>
                <th>Name</th>
                <th>Category</th>
                <th>Type</th>
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
    include_once('../layouts/product_purchasing_end.php');
    include_once('../partials/modals.php');
?>
<div id="remove-modal" class="modal fade" data-id="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Remove Product from Wishlist</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove this product from wishlist?</p>
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
<script src="../js/custom/product_purchasing/wishlist.js"></script>
<?php
    include_once('../layouts/footer.php');
?>
