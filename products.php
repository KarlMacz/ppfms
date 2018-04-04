<?php
    session_start();

    require_once('backend/database.php');
    require_once('backend/functions.php');

    include_once('layouts/header.php');

    if(isset($_SESSION['user_id']) && $_SESSION['type'] === 'Administrator') {
        header('Location: factory_management/index.php');

        exit();
    }
?>
<nav class="navbar navbar-default navbar-fixed-top no-margin">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="navbar-brand">Bhagi's International Trading Corporation</div>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="index.php#home-section">Home</a></li>
                <li><a href="index.php#about-us-section">About Us</a></li>
                <li><a href="index.php#contact-us-section">Contact Us</a></li>
                <li class="active"><a href="products.php">Products</a></li>
                <?php
                    if(isset($_SESSION['user_id'])) {
                ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['full_name']; ?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="product_purchasing/index.php">Product Purchasing</a></li>
                        <li><a href="profile.php">Profile</a></li>
                        <li class="divider"></li>
                        <li><a href="../logout.php">Logout</a></li>
                    </ul>
                </li>
                <?php
                    } else {
                ?>
                <li><a href="login.php">Login</a></li>
                <?php
                    }
                ?>
            </ul>
        </div>
    </div>
</nav>
<section class="hero">
    <div class="hero-content">
        <div class="container">
            <div class="content">
                <form action="" method="GET">
                    <div class="form-group">
                        <div class="input-group input-group-lg">
                            <input type="text" name="search_for" class="form-control" placeholder="Search" value="<?php echo (isset($_GET['search_for']) ? $_GET['search_for'] : ''); ?>" autofocus>
                            <span class="input-group-btn">
                                <button type="submit" class="search-button btn btn-primary"><span class="fas fa-search fa-fw"></span></button>
                            </span>
                        </div>
                    </div>
                </form>
                <?php
                    $page = isset($_GET['page']) && $_GET['page'] !== '' ? (int) input_escape_string($connection, $_GET['page']) : 1;
                    $limit = 15;
                    $start = ($page * $limit) - $limit;

                    if(isset($_GET['search_for']) && $_GET['search_for'] !== '') {
                        $searchFor = input_escape_string($connection, $_GET['search_for']);

                        $queryAll = mysqli_query($connection, "SELECT * FROM `products`
                            WHERE `name` LIKE '%$searchFor%' OR `product_code`='$searchFor'");
                        $query = mysqli_query($connection, "SELECT * FROM `products`
                            WHERE `name` LIKE '%$searchFor%' OR `product_code`='$searchFor'
                            LIMIT $start, $limit");
                    } else {
                        $queryAll = mysqli_query($connection, "SELECT * FROM `products`");
                        $query = mysqli_query($connection, "SELECT * FROM `products`
                            LIMIT $start, $limit");
                    }

                    $countAll = mysqli_num_rows($queryAll);
                    $count = mysqli_num_rows($query);
                ?>
                <div class="text-right" style="margin-bottom: 10px;"><?php echo 'Showing ' . ($start + 1) . '-' . ($start + $count) . ' of ' . $countAll . ' result(s).' ?></div>
                <div class="well">
                    <?php
                        if($countAll > 0) {
                            $colCtr = 0;

                            while($row = mysqli_fetch_assoc($query)) {
                                if($row['image'] != null) {
                                    $image = $row['image'];
                                } else {
                                    $image = 'placeholder.png';
                                }

                                if($colCtr % 3 === 0) {
                    ?>
                    <div class="row">
                    <?php
                                }

                    ?>
                    <div class="col-sm-4">
                        <div class="card" style="margin-bottom: 25px;">
                            <div class="card-image">
                                <img src="uploads/products/<?php echo $image; ?>">
                            </div>
                            <div class="card-content">
                                <h3 style="margin-top: 0; margin-bottom: 10px;"><?php echo $row['name']; ?></h3>
                                <p><?php echo nl2br($row['description']); ?></p>
                                <h3 class="text-right no-margin">Php <?php echo number_format($row['item_price'], 2); ?></h3>
                            </div>
                            <div class="card-footer">
                                <?php
                                    if(isset($_SESSION['user_id'])) {
                                ?>
                                <button type="button" class="add-to-wishlist-button card-footer-item" data-id="<?php echo $row['id']; ?>"><span class="fas fa-heart fa-fw"></span> Add to Wishlist</button>
                                <button type="button" class="add-to-cart-button card-footer-item" data-id="<?php echo $row['id']; ?>" data-name="<?php echo $row['name']; ?>" data-available="<?php echo $row['quantity_available']; ?>"><span class="fas fa-shopping-cart fa-fw"></span> Add to Cart</button>
                                <?php
                                    } else {
                                ?>
                                <a href="login.php" class="card-footer-item">Login to Order</a>
                                <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                                if($colCtr % 3 === 2 || $colCtr === $count - 1) {
                    ?>
                    </div>
                    <?php
                                }

                                $colCtr++;
                            }
                        }
                    ?>
                </div>
                <nav class="text-right">
                    <?php
                        if(isset($_SESSION['user_id'])) {
                            $userID = $_SESSION['user_id'];

                            $cartQuery = mysqli_query($connection, "SELECT * FROM `carts` WHERE `account_id`='$userID'");
                            $cartCount = mysqli_num_rows($cartQuery);
                    ?>
                    <a href="product_purchasing/cart.php" class="btn btn-default pull-left"><span class="fas fa-shopping-cart fa-fw"></span> View Cart<?php echo ($cartCount > 0 ? ' <span class="badge">' . $cartCount . '</span>' : '') ?></a>
                    <?php
                        }
                    ?>
                    <ul class="pagination no-margin">
                        <li<?php echo ($page === 1 ? ' class="disabled"' : ''); ?>><a<?php echo ($page > 1 ? ' href="products.php?' . (isset($_GET['search_for']) ? 'search_for=' . $_GET['search_for'] . '&' : '') . 'page=' . ($page > 1 ? $page - 1 : 1) . '"' : ''); ?>><span>&laquo;</span></a></li>
                        <?php
                            for($i = 0; $i < (int) ceil($countAll / $limit); $i++) {
                        ?>
                        <li<?php echo ($page === ($i + 1) ? ' class="active"' : ''); ?>><a href="products.php?<?php echo (isset($_GET['search_for']) ? 'search_for=' . $_GET['search_for'] . '&' : ''); ?>page=<?php echo ($i + 1); ?>"><span><?php echo ($i + 1); ?></span></a></li>
                        <?php
                            }
                        ?>
                        <li<?php echo ($page === (int) ceil($countAll / $limit) ? ' class="disabled"' : ''); ?>><a<?php echo ($page < (int) ceil($countAll / $limit) ? ' href="products.php?' . (isset($_GET['search_for']) ? 'search_for=' . $_GET['search_for'] . '&' : '') . 'page=' . ($page < ceil($countAll / $limit) ? $page + 1 : 1) . '"': ''); ?>><span>&raquo;</span></a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>
<footer class="new-footer has-text-grey is-light">
    <div class="container">
        <div class="col-sm-6 text-left">
            <span>© Copyright <?php echo date('Y') . ' ' . COMPANY_NAME; ?>.</span>
        </div>
        <div class="col-sm-6 text-right">
            <span>All Rights Reserved.</span>
        </div>
    </div>
</footer>
<?php
    include_once('partials/modals.php');
?>
<div id="add-to-cart-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add to Cart</h4>
            </div>
            <div class="modal-body">
                <form id="add-to-cart-form">
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
<script src="js/custom/products.js"></script>
<?php
    include_once('layouts/footer.php');
?>
