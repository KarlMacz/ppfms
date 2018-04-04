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
            <div class="navbar-brand"><span class="fas fa-th-list fa-fw"></span> Inventory Listing</div>
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
    <table id="products-table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th width="10%">Product Code</th>
                <th>Name</th>
                <th>Description</th>
                <th width="10%">In Stock</th>
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
<div id="view-modal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">View Stocks</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<div id="fetch-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Fetch Box</h4>
            </div>
            <div class="modal-body">
                <p><strong>Note</strong>: 1 box will be removed from this product's inventory.</p>
                <form id="fetch-form">
                    <div>
                        <input type="hidden" name="id" value="">
                    </div>
                    <div class="form-group no-margin">
                        <label for="quantity-input">Box Quantity:</label>
                        <input type="number" name="quantity" id="quantity-input" class="form-control" min="1" placeholder="Box Quantity" required autofocus>
                    </div>
                </form>
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
<div id="excess-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Excess Material Registry</h4>
            </div>
            <div class="modal-body">
                <form id="excess-form">
                    <div>
                        <input type="hidden" name="id" value="">
                    </div>
                    <div class="form-group no-margin">
                        <label for="quantity-input">Excess Quantity:</label>
                        <input type="number" name="quantity" id="quantity-input" class="form-control" min="1" placeholder="Excess Quantity" required autofocus>
                    </div>
                </form>
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
<div id="print-qr-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Print QR Code</h4>
            </div>
            <div class="modal-body">
                <iframe src="" frameborder="0" id="qr-frame" class="page-framing"></iframe>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<div id="issue-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Issue Registry</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <div class="text-left">
                    <form id="issue-form">
                        <div>
                            <input type="hidden" name="id" value="">
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <label class="input-group-addon" for="issue-input">Issue:</label>
                                <input type="text" name="issue" id="issue-input" class="form-control" placeholder="Issue" required autofocus>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="text-right">
                    <button type="button" class="negative-button btn btn-default"><span class="fas fa-times fa-fw"></span> No</button>
                    <button type="button" class="positive-button btn btn-danger"><span class="fas fa-check fa-fw"></span> Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="remove-issue-modal" class="modal fade" data-id="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Remove Issue</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove this issue?</p>
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
<script src="../js/custom/factory_management/inventory_listing.js"></script>
<?php
    include_once('../layouts/footer.php');
?>
