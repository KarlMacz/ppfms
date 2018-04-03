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
            <div class="navbar-brand"><span class="fas fa-plus fa-fw"></span> Supply Replacement</div>
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
    <div class="container-plasma">
        <h3 class="page-header">Supply Replacement</h3>
        <?php
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $product = input_escape_string($connection, $_POST['product']);
                $supplier = input_escape_string($connection, $_POST['supplier']);
                $quantity = input_escape_string($connection, $_POST['quantity']);
                $orderDate = input_escape_string($connection, $_POST['order_date']);

                echo $orderDate;

                $query = mysqli_query($connection, "INSERT INTO `inventories` (`product_id`, `supplier_id`, `boxes_ordered`, `boxes_in_stock`, `date_ordered`, `created_at`) VALUES ('$product', '$supplier', '$quantity', '$quantity', '$orderDate', '$today')");

                if(mysqli_affected_rows($connection) === 1) {
        ?>
        <div class="alert alert-success">Supply has been added.</div>
        <?php
                } else {
        ?>
        <div class="alert alert-danger">Failed to add supply.</div>
        <?php
                }
            }
        ?>
        <form action="" method="POST">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="product-input">Product:</label>
                        <select name="product" id="product-input" class="form-control" required autofocus>
                            <option value="" selected disabled>Select an option...</option>
                            <?php
                                $query = mysqli_query($connection, "SELECT * FROM `products`");

                                while($row = mysqli_fetch_assoc($query)) {
                            ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="supplier-input">Supplier:</label>
                        <select name="supplier" id="supplier-input" class="form-control" required disabled>
                            <option value="" selected disabled>Select an option...</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="quantity-input">Quantity Supplied:</label>
                        <input type="text" name="quantity" id="quantity-input" class="form-control" placeholder="Quantity Supplied" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="order-date-input">Date Ordered:</label>
                        <input type="date" name="order_date" id="order-date-input" class="form-control" placeholder="Date Ordered" required>
                    </div>
                </div>
            </div>
            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary"><span class="fas fa-check fa-fw"></span> Add Supply</button>
            </div>
        </form>
    </div>
</div>
<?php
    include_once('../layouts/factory_management_end.php');
    include_once('../partials/modals.php');
?>
<script src="../js/custom/factory_management/supply_replacement.js"></script>
<?php
    include_once('../layouts/footer.php');
?>
