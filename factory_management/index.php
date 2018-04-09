<?php
    session_start();

    require_once('../backend/database.php');
    require_once('../backend/functions.php');

    include_once('../layouts/authorized_header.php');
    include_once('../layouts/factory_management_start.php');

    $criticalLevel = 1;

    $settingsQuery = mysqli_query($connection, "SELECT * FROM `settings` WHERE `name`='critical_level'");

    if(mysqli_num_rows($settingsQuery) === 1) {
        $settingsRow = mysqli_fetch_assoc($settingsQuery);
        $criticalLevel = $settingsRow['value'];
    }
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
            <div class="navbar-brand"><span class="fas fa-tachometer-alt fa-fw"></span> Dashboard</div>
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
    <div class="col-sm-8"></div>
    <div class="col-sm-4">
        <h3 class="page-header">Critical Level</h3>
        <div class="list-group">
            <?php
                $query = mysqli_query($connection, "SELECT * FROM `products`");

                if(mysqli_num_rows($query) > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        $productID = $row['id'];

                        $query2 = mysqli_query($connection, "SELECT `boxes_in_stock` FROM `inventories`
                            WHERE `product_id`='$productID'");

                        $stocks = 0;

                        while($row2 = mysqli_fetch_assoc($query2)) {
                            $stocks += $row2['boxes_in_stock'];
                        }

                        if($stocks <= $criticalLevel) {
            ?>
            <div class="list-group-item">
                <h4 class="list-group-item-heading"><?php echo $row['name']; ?></h4>
                <div><?php echo $row['boxes_in_stock'] . ($row['boxes_in_stock'] > 1) ? ' boxes' : 'box'; ?> left.</div>
            </div>
            <?php
                        }
                    }
                } else {
            ?>
            <div class="list-group-item text-center">None at the moment.</div>
            <?php
                }
            ?>
        </div>
    </div>
</div>
<?php
    include_once('../layouts/factory_management_end.php');
    include_once('../partials/modals.php');
    include_once('../layouts/footer.php');
?>
