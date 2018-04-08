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
            <div class="navbar-brand"><span class="fas fa-arrow-right fa-fw"></span> Production Process Tracking</div>
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
        <div class="col-sm-9">
            <button type="button" class="add-production-button btn btn-primary"><span class="fas fa-plus fa-fw"></span> Add Batch</button>
        </div>
        <div class="col-sm-3">
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
                <th width="20%">Date & Time Added</th>
                <th width="10%">Status</th>
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
<div id="add-production-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Batch</h4>
            </div>
            <div class="modal-body">
                <form id="add-production-form">
                    <div class="form-group">
                        <label for="product-input">Product:</label>
                        <select name="product" id="product-input" class="form-control" required autofocus>
                            <option value="" selected disabled>Select an option...</option>
                            <?php
                                $productsQuery = mysqli_query($connection, "SELECT * FROM `products`");

                                if(mysqli_num_rows($productsQuery) > 0) {
                                    while($productsRow = mysqli_fetch_assoc($productsQuery)) {
                            ?>
                            <option value="<?php echo $productsRow['id']; ?>"><?php echo $productsRow['name']; ?></option>
                            <?php
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity-input">Quantity (Box):</label>
                        <input type="number" name="quantity" id="quantity-input" class="form-control" min="1" placeholder="Quantity (Box)" required>
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
<div id="extra-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Extra Product Information</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <div class="text-left">
                    <form id="extra-form">
                        <div>
                            <input type="hidden" name="id" value="">
                        </div>
                        <div class="form-group">
                            <label for="information-input">Additional Information:</label>
                            <input type="text" name="information" id="information-input" class="form-control" placeholder="Additional Information" required autofocus>
                        </div>
                    </form>
                </div>
                <div class="text-right">
                    <button type="button" class="negative-button btn btn-default"><span class="fas fa-times fa-fw"></span> Cancel</button>
                    <button type="button" class="positive-button btn btn-primary"><span class="fas fa-check fa-fw"></span> Add</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="finished-modal" class="modal fade" data-id="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Mark Batch as Finished</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to mark this batch as finished? Finished batch will automatically be added to the product's inventory.</p>
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
<div id="remove-extra-modal" class="modal fade" data-id="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Remove Extra Product Information</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove this extra product information?</p>
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
<script src="../js/custom/factory_management/production_process_tracking.js"></script>
<?php
    include_once('../layouts/footer.php');
?>
