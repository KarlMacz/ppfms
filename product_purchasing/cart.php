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
            <div class="navbar-brand"><span class="fas fa-shopping-cart fa-fw"></span> Cart</div>
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
    <?php
        $userID = $_SESSION['user_id'];
    
        $query = mysqli_query($connection, "SELECT *,
                `carts`.`id` AS `cart_id`
            FROM `carts`
            INNER JOIN `products`
                ON `carts`.`product_id`=`products`.`id`
            WHERE `carts`.`account_id`='$userID'");

        if(mysqli_num_rows($query) > 0) {
    ?>
    <div class="form-group text-right">
        <button type="button" class="checkout-button btn btn-primary"><span class="fas fa-check fa-fw"></span> Checkout</button>
    </div>
    <?php
        }
    ?>
    <table id="carts-table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
                <th>Total</th>
                <th width="10%">Action(s)</th>
            </tr>
        </thead>
        <tbody></tbody>
        <tfoot></tfoot>
    </table>
</div>
<?php
    include_once('../layouts/product_purchasing_end.php');
    include_once('../partials/modals.php');
?>
<div id="edit-quantity-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Quantity</h4>
            </div>
            <div class="modal-body">
                <form id="edit-quantity-form">
                    <div>
                        <input type="hidden" name="cart_id" value="">
                    </div>
                    <div class="form-group">
                        <label>Product Name:</label>
                        <input type="text" id="name-input" class="form-control" value="" readonly>
                    </div>
                    <div class="form-group no-margin">
                        <label for="quantity-input">Quantity:</label>
                        <input type="number" step="any" name="quantity" id="quantity-input" class="form-control" min="1" placeholder="Quantity" value="" required autofocus>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="text-right">
                    <button type="button" class="negative-button btn btn-default"><span class="fas fa-times fa-fw"></span> Cancel</button>
                    <button type="button" class="positive-button btn btn-success"><span class="fas fa-check fa-fw"></span> Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="remove-from-cart-modal" class="modal fade" data-id="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Remove from Cart</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove this product from the cart?</p>
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
<div id="remove-all-from-cart-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Remove All from Cart</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove all products from the cart?</p>
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
<div id="checkout-modal" class="modal fade" data-id="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Checkout</h4>
            </div>
            <div class="modal-body">
                <form id="checkout-form">
                    <div>
                        <input type="hidden" name="shipping_fee" value="">
                    </div>
                    <div class="form-group">
                        <label for="payment-method-input">Payment Method:</label>
                        <select name="payment_method" id="payment-method-input" class="form-control" required>
                            <option value="" selected disabled>Select an option...</option>
                            <option value="Cash on Delivery">Cash on Delivery</option>
                            <option value="PayPal">PayPal</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="billing-address-input">Billing Address:</label>
                                <select name="billing_address" id="billing-address-input" class="form-control" required>
                                    <option value="" selected disabled>Select an option...</option>
                                    <?php
                                        $billingAddressesQuery = mysqli_query($connection, "SELECT * FROM `billing_addresses` WHERE `account_id`='$userID'");

                                        if(mysqli_num_rows($billingAddressesQuery)) {
                                            while($billingAddressesRow = mysqli_fetch_assoc($billingAddressesQuery)) {
                                    ?>
                                    <option value="<?php echo $billingAddressesRow['id']; ?>">
                                        <?php
                                            if($billingAddressesRow['middle_name'] != null) {
                                                $billingAddressesName = $billingAddressesRow['first_name'] . ' ' . substr($billingAddressesRow['middle_name'], 0, 1) . '. ' . $billingAddressesRow['last_name'];
                                            } else {
                                                $billingAddressesName = $billingAddressesRow['first_name'] . ' ' . $billingAddressesRow['last_name'];
                                            }

                                            echo $billingAddressesName . ' - ' . $billingAddressesRow['billing_address'];
                                        ?>
                                    </option>
                                    <?php
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="shipping-address-input">Shipping Address:</label>
                                <select name="shipping_address" id="shipping-address-input" class="form-control" required>
                                    <option value="" selected disabled>Select an option...</option>
                                    <?php
                                        $shippingAddressesQuery = mysqli_query($connection, "SELECT * FROM `shipping_addresses` WHERE `account_id`='$userID'");

                                        if(mysqli_num_rows($shippingAddressesQuery)) {
                                            while($shippingAddressesRow = mysqli_fetch_assoc($shippingAddressesQuery)) {
                                    ?>
                                    <option value="<?php echo $shippingAddressesRow['id']; ?>" data-address="<?php echo $shippingAddressesRow['shipping_address']; ?>">
                                        <?php
                                            if($shippingAddressesRow['middle_name'] != null) {
                                                $shippingAddressesName = $shippingAddressesRow['first_name'] . ' ' . substr($shippingAddressesRow['middle_name'], 0, 1) . '. ' . $shippingAddressesRow['last_name'];
                                            } else {
                                                $shippingAddressesName = $shippingAddressesRow['first_name'] . ' ' . $shippingAddressesRow['last_name'];
                                            }

                                            echo $shippingAddressesName . ' - ' . $shippingAddressesRow['shipping_address'];
                                        ?>
                                    </option>
                                    <?php
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="shipping-fee-block" style="border-top: 2px dashed #777; padding-top: 25px; margin-top: 10px;">
                    <div class="alert alert-info">Select a shipping address to know shipping fee.</div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="text-right">
                    <button type="button" class="negative-button btn btn-default"><span class="fas fa-times fa-fw"></span> Cancel</button>
                    <button type="button" class="positive-button btn btn-primary"><span class="fas fa-check fa-fw"></span> Checkout</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="../js/custom/product_purchasing/cart.js"></script>
<?php
    include_once('../layouts/footer.php');
?>
