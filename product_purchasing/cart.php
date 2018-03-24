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
            <div class="navbar-brand"><span class="fas fa-shopping-cart fa-fw"></span> Cart</div>
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
    <div class="form-group text-right">
        <button type="button" class="checkout-button btn btn-primary btn-lg"><span class="fas fa-check fa-fw"></span> Checkout</button>
    </div>
    <table id="carts-table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
                <th>Total</th>
                <th width="10%">Action(s)</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $totalAmount = 0;
                $userID = $_SESSION['user_id'];

                $query = mysqli_query($connection, "SELECT *,
                        `carts`.`id` AS `cart_id`
                    FROM `carts`
                    INNER JOIN `products`
                        ON `carts`.`product_id`=`products`.`id`
                    WHERE `carts`.`account_id`='$userID'");

                if(mysqli_num_rows($query) > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        $itemTotal = ((double) $row['quantity']) * $row['item_price'];
                        $totalAmount += $itemTotal;
            ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td class="text-right">Php <?php echo number_format($itemTotal, 2); ?></td>
                <td class="text-center">
                    <button type="button" class="edit-button btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Edit Quantity" data-id="<?php echo $row['cart_id']; ?>" data-name="<?php echo $row['name']; ?>" data-quantity="<?php echo $row['quantity']; ?>" data-available="<?php echo $row['quantity_available']; ?>"><span class="fas fa-edit fa-fw"></span></button>
                    <button type="button" class="remove-button btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Remove from Cart" data-id="<?php echo $row['cart_id']; ?>"><span class="fas fa-trash fa-fw"></span></button>
                </td>
            </tr>
            <?php
                    }
                } else {
            ?>
            <tr>
                <td class="text-center" colspan="4">No results found.</td>
            </tr>
            <?php
                }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th class="text-right" colspan="2">Total Amount:</th>
                <th class="text-right">Php <?php echo number_format($totalAmount, 2); ?></th>
                <th></th>
            </tr>
        </tfoot>
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
<div id="checkout-modal" class="modal fade" data-id="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Checkout</h4>
            </div>
            <div class="modal-body">
                <form id="checkout-form">
                    <div class="form-group">
                        <label for="payment-method-input">Payment Method:</label>
                        <select name="payment_method" id="payment-method-input" class="form-control" required autofocus>
                            <option value="" selected disabled>Select an option...</option>
                            <option value="Cash on Delivery">Cash on Delivery</option>
                            <option value="PayPal">PayPal</option>
                        </select>
                    </div>
                </form>
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
