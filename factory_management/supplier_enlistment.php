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
            <div class="navbar-brand"><span class="fas fa-plus fa-fw"></span> Supplier Enlistment</div>
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
        <h3 class="page-header">Supplier Enlistment</h3>
        <?php
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = input_escape_string($connection, $_POST['name']);
                $logo = $_FILES['logo'];
                $description = input_escape_string($connection, $_POST['description']);
                $supplies = $_POST['supplies'];

                $filetype = null;
                $logoFilename = null;

                if(empty($logo['name'])) {
                    $query = mysqli_query($connection, "INSERT INTO `suppliers` (`name`, `description`) VALUES ('$name', '$description')");

                    if(mysqli_affected_rows($connection) === 1) {
                        $supplierID = mysqli_insert_id($connection);

                        foreach($supplies as $supply) {
                            $productID = $supply;

                            mysqli_query($connection, "INSERT INTO `supplies` (`supplier_id`, `product_id`) VALUES ('$supplierID', '$productID')");
                        }

        ?>
        <div class="alert alert-success">Supplier has been enlisted.</div>
        <?php
                    } else {
        ?>
        <div class="alert alert-danger">Failed to enlist supplier.</div>
        <?php
                    }
                } else {
                    $filetype = strtolower(pathinfo($logo['name'], PATHINFO_EXTENSION));

                    if($filetype === 'png' || $filetype === 'jpeg' || $filetype === 'jpg') {
                        $targetLogo = date('Ymd_His') . basename($logo['name']);

                        $query = mysqli_query($connection, "INSERT INTO `suppliers` (`name`, `description`, `logo`) VALUES ('$name', '$description', '$targetLogo')");

                        if(mysqli_affected_rows($connection) === 1) {
                            $supplierID = mysqli_insert_id($connection);

                            foreach($supplies as $supply) {
                                $productID = $supply;

                                mysqli_query($connection, "INSERT INTO `supplies` (`supplier_id`, `product_id`) VALUES ('$supplierID', '$productID')");
                            }
                            
                            if(move_uploaded_file($logo['tmp_name'], '../uploads/suppliers/' . $targetLogo)) {
        ?>
        <div class="alert alert-success">Supplier has been enlisted.</div>
        <?php
                            } else {
        ?>
        <div class="alert alert-warning">Supplier has been enlisted but logo upload failed.</div>
        <?php
                            }
                        } else {
        ?>
        <div class="alert alert-danger">Failed to enlist supplier.</div>
        <?php
                        }
                    } else {
        ?>
        <div class="alert alert-danger">Invalid logo file type.</div>
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
                        <label for="logo-input">Logo:</label>
                        <input type="file" name="logo" id="logo-input" class="form-control" placeholder="Logo">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="description-input">Description:</label>
                        <textarea name="description" id="description-input" rows="5" class="form-control no-resize" placeholder="Description" required></textarea>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Supplies Provided:</label>
                        <div class="well">
                            <div class="form-group">
                                <button type="button" class="add-supply-button btn btn-primary btn-sm"><span class="fas fa-plus fa-fw"></span> Add</button>
                            </div>
                            <div id="supplies-provided-block">
                                <div class="form-group">
                                    <select name="supplies[]" class="form-control" required>
                                        <option value="" selected disabled>Select an option...</option>
                                        <?php
                                            $query = mysqli_query($connection, "SELECT * FROM `products`");

                                            if(mysqli_num_rows($query) > 0) {
                                                while($row = mysqli_fetch_assoc($query)) {
                                        ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary"><span class="fas fa-check fa-fw"></span> Enlist Supplier</button>
            </div>
        </form>
    </div>
</div>
<?php
    include_once('../layouts/factory_management_end.php');
    include_once('../partials/modals.php');
?>
<script src="../js/custom/factory_management/supplier_enlistment.js"></script>
<?php
    include_once('../layouts/footer.php');
?>
