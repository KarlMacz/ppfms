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
            <div class="navbar-brand"><span class="fas fa-cubes fa-fw"></span> Product Registration</div>
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
        <h3 class="page-header">Product Registration</h3>
        <?php
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = input_escape_string($connection, $_POST['name']);
                $image = $_FILES['image'];
                $description = input_escape_string($connection, $_POST['description']);
                $type = input_escape_string($connection, $_POST['type']);
                $category = input_escape_string($connection, $_POST['category']);
                $price = input_escape_string($connection, $_POST['price']);

                $filetype = null;

                if(empty($image['name'])) {
                    $productCode = generate_product_code($connection);

                    $query = mysqli_query($connection, "INSERT INTO `products` (`product_code`, `name`, `description`, `type`, `category`, `item_price`, `created_at`) VALUES ('$productCode', '$name', '$description', '$type', '$category', '$price', '$today')");

                    if(mysqli_affected_rows($connection) === 1) {
        ?>
        <div class="alert alert-success">Product has been registered.</div>
        <?php
                    } else {
        ?>
        <div class="alert alert-danger">Failed to register product.</div>
        <?php
                    }
                } else {
                    $filetype = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

                    if($filetype === 'png' || $filetype === 'jpeg' || $filetype === 'jpg') {
                        $targetImage = date('Ymd_His') . basename($image['name']);

                        $query = mysqli_query($connection, "INSERT INTO `products` (`name`, `description`, `type`, `category`, `item_price`, `image`, `created_at`) VALUES ('$name', '$description', '$type', '$category', '$price', '$targetImage', '$today')");

                        if(mysqli_affected_rows($connection) === 1) {
                            if(move_uploaded_file($image['tmp_name'], '../uploads/products/' . $targetImage)) {
        ?>
        <div class="alert alert-success">Product has been registered.</div>
        <?php
                            } else {
        ?>
        <div class="alert alert-warning">Product has been registered but image upload failed.</div>
        <?php
                            }
                        } else {
        ?>
        <div class="alert alert-danger">Failed to register product.</div>
        <?php
                        }
                    } else {
        ?>
        <div class="alert alert-danger">Invalid image file type.</div>
        <?php
                    }
                }
            }
        ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name-input">Name:</label>
                        <input type="text" name="name" id="name-input" class="form-control" placeholder="Name" required autofocus>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="image-input">Image:</label>
                        <input type="file" name="image" id="image-input" class="form-control" placeholder="Image">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="type-input">Type:</label>
                        <select name="type" id="type-input" class="form-control" required>
                            <option value="" selected disabled>Select an option...</option>
                            <option value="Face">Face</option>
                            <option value="Body">Body</option>
                            <option value="Hands/Nails">Hands/Nails</option>
                            <option value="Hair">Hair</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="category-input">Category:</label>
                        <select name="category" id="category-input" class="form-control" required disabled>
                            <option value="" selected disabled>Select an option...</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="price-input">Price:</label>
                        <input type="number" step="any" name="price" id="price-input" class="form-control" placeholder="Price" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="description-input">Description:</label>
                <textarea name="description" id="description-input" rows="5" class="form-control no-resize" placeholder="Description" required></textarea>
            </div>
            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary"><span class="fas fa-check fa-fw"></span> Register Product</button>
            </div>
        </form>
    </div>
</div>
<?php
    include_once('../layouts/factory_management_end.php');
    include_once('../partials/modals.php');
?>
<script src="../js/custom/factory_management/product_registration.js"></script>
<?php
    include_once('../layouts/footer.php');
?>
