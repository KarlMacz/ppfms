<?php
    session_start();

    require_once('../backend/database.php');
    require_once('../backend/functions.php');

    include_once('../layouts/authorized_header.php');
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
            <div class="navbar-brand"><span class="fas fa-address-book fa-fw"></span> Address Book</div>
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
            <div class="form-group text-right">
                <button type="button" class="add-billing-address-button btn btn-primary btn-lg"><span class="fas fa-plus fa-fw"></span> Add Billing Address</button>
            </div>
            <table id="billing-address-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Billing Address</th>
                        <th width="10%">Action(s)</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <nav class="text-right">
                <ul class="billing-address-pagination pagination no-margin"></ul>
            </nav>
        </div>
        <div class="col-sm-6">
            <div class="form-group text-right">
                <button type="button" class="add-shipping-address-button btn btn-primary btn-lg"><span class="fas fa-plus fa-fw"></span> Add Shipping Address</button>
            </div>
            <table id="shipping-address-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Shipping Address</th>
                        <th width="10%">Action(s)</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <nav class="text-right">
                <ul class="shipping-address-pagination pagination no-margin"></ul>
            </nav>
        </div>
    </div>
</div>
<?php
    include_once('../layouts/product_purchasing_end.php');
    include_once('../partials/modals.php');
?>
<div id="view-product-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">View Product Information</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
<div id="add-billing-address-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Billing Address</h4>
            </div>
            <div class="modal-body">
                <form id="add-billing-address-form">
                    <div>
                        <input type="hidden" name="id" value="">
                    </div>
                    <div class="form-group">
                        <label>Product Name:</label>
                        <input type="text" id="name-input" class="form-control" value="" readonly>
                    </div>
                    <div class="form-group">
                        <label for="quantity-input">Quantity:</label>
                        <input type="number" step="any" name="quantity" id="quantity-input" class="form-control" min="1" placeholder="Quantity" required autofocus>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="text-right">
                    <button type="button" class="negative-button btn btn-default"><span class="fas fa-times fa-fw"></span> Cancel</button>
                    <button type="button" class="positive-button btn btn-primary"><span class="fas fa-check fa-fw"></span> Add</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="add-shipping-address-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Shipping Address</h4>
            </div>
            <div class="modal-body">
                <form id="add-shipping-address-form">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="first-name-input">First Name:</label>
                                <input type="text" name="first_name" id="first-name-input" class="form-control" placeholder="First Name" required autofocus>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="middle-name-input">Middle Name:</label>
                                <input type="text" name="middle_name" id="middle-name-input" class="form-control" placeholder="Middle Name">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="last-name-input">Last Name:</label>
                                <input type="text" name="last_name" id="last-name-input" class="form-control" placeholder="Last Name" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address-input">Address:</label>
                        <input type="text" name="address" id="address-input" class="form-control" placeholder="Address" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="text-right">
                    <button type="button" class="negative-button btn btn-default"><span class="fas fa-times fa-fw"></span> Cancel</button>
                    <button type="button" class="positive-button btn btn-primary"><span class="fas fa-check fa-fw"></span> Add</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="remove-billing-address-modal" class="modal fade" data-id="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Remove Billing Address</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove this billing address?</p>
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
<div id="remove-shipping-address-modal" class="modal fade" data-id="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Remove Shipping Address</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove this shipping address?</p>
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
<script src="../js/custom/product_purchasing/address_book.js"></script>
<?php
    include_once('../layouts/footer.php');
?>
