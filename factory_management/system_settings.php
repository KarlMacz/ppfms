<?php
    session_start();

    require_once('../backend/database.php');
    require_once('../backend/functions.php');

    include_once('../layouts/authorized_header.php');
    include_once('../layouts/factory_management_start.php');

    function checkSettings($connection, $name) {
        $query = mysqli_query($connection, "SELECT * FROM `settings` WHERE `name`='$name'");

        if(mysqli_num_rows($query) === 1) {
            return true;
        } else {
            return false;
        }
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
            <div class="navbar-brand"><span class="fas fa-cogs fa-fw"></span> System Settings</div>
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
        <h3 class="page-header">System Settings</h3>
        <?php
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                if($_POST['what'] === 'system_settings') {
                    $shippingFeeWithin = isset($_POST['shipping_fee_within']) && $_POST['shipping_fee_within'] !== '' ? input_escape_string($connection, $_POST['shipping_fee_within']) : 1;
                    $shippingFeeOutside = isset($_POST['shipping_fee_outside']) && $_POST['shipping_fee_outside'] !== '' ? input_escape_string($connection, $_POST['shipping_fee_outside']) : 1;
                    $criticalLevel = isset($_POST['critical_level']) && $_POST['critical_level'] !== '' ? input_escape_string($connection, $_POST['critical_level']) : 1;

                    $ctr = 0;

                    if(checkSettings($connection, 'shipping_fee_within_metro_manila')) {
                        mysqli_query($connection, "UPDATE `settings` SET `value`='$shippingFeeWithin' WHERE `name`='shipping_fee_within_metro_manila'");

                        if(mysqli_affected_rows($connection)) {
                            $ctr++;
                        }
                    } else {
                        mysqli_query($connection, "INSERT INTO `settings` (`name`, `value`) VALUES ('shipping_fee_within_metro_manila', '$shippingFeeWithin')");

                        if(mysqli_affected_rows($connection)) {
                            $ctr++;
                        }
                    }

                    if(checkSettings($connection, 'shipping_fee_outside_metro_manila')) {
                        mysqli_query($connection, "UPDATE `settings` SET `value`='$shippingFeeOutside' WHERE `name`='shipping_fee_outside_metro_manila'");

                        if(mysqli_affected_rows($connection)) {
                            $ctr++;
                        }
                    } else {
                        mysqli_query($connection, "INSERT INTO `settings` (`name`, `value`) VALUES ('shipping_fee_outside_metro_manila', '$shippingFeeOutside')");

                        if(mysqli_affected_rows($connection)) {
                            $ctr++;
                        }
                    }

                    if(checkSettings($connection, 'critical_level')) {
                        mysqli_query($connection, "UPDATE `settings` SET `value`='$criticalLevel' WHERE `name`='critical_level'");

                        if(mysqli_affected_rows($connection)) {
                            $ctr++;
                        }
                    } else {
                        mysqli_query($connection, "INSERT INTO `settings` (`name`, `value`) VALUES ('critical_level', '$criticalLevel')");

                        if(mysqli_affected_rows($connection)) {
                            $ctr++;
                        }
                    }

                    if($ctr > 0) {
        ?>
        <div class="alert alert-success">All changes have been saved.</div>
        <?php
                    } else {
        ?>
        <div class="alert alert-danger">No changes have been made.</div>
        <?php
                    }
                }
            }

            $shippingFeeWithinMetroManila = null;
            $shippingFeeOutsideMetroManila = null;
            $criticalLevel = null;

            $query = mysqli_query($connection, "SELECT * FROM `settings`");
            
            if(mysqli_num_rows($query) > 0) {
                while($row = mysqli_fetch_assoc($query)) {
                    switch($row['name']) {
                        case 'shipping_fee_within_metro_manila':
                            $shippingFeeWithinMetroManila = $row['value'];

                            break;
                        case 'shipping_fee_outside_metro_manila':
                            $shippingFeeOutsideMetroManila = $row['value'];

                            break;
                        case 'critical_level':
                            $criticalLevel = $row['value'];

                            break;
                    }
                }
            }
        ?>
        <form action="" method="POST">
            <div>
                <input type="hidden" name="what" value="system_settings">
            </div>
            <div class="form-group">
                <label for="shipping-fee-within-input">Shipping Fee (Within Metro Manila):</label>
                <input type="number" step="any" name="shipping_fee_within" id="shipping-fee-within-input" class="form-control input-lg" min="1" placeholder="Shipping Fee (Within Metro Manila)" value="<?php echo ($shippingFeeWithinMetroManila != null ? $shippingFeeWithinMetroManila : 1); ?>" required>
            </div>
            <div class="form-group">
                <label for="shipping-fee-outside-input">Shipping Fee (Outside Metro Manila):</label>
                <input type="number" step="any" name="shipping_fee_outside" id="shipping-fee-outside-input" class="form-control input-lg" min="1" placeholder="Shipping Fee (Outside Metro Manila)" value="<?php echo ($shippingFeeOutsideMetroManila != null ? $shippingFeeOutsideMetroManila : 1); ?>" required>
            </div>
            <div class="form-group">
                <label for="critical-level-input">Inventory Critical Level:</label>
                <input type="number" step="any" name="critical_level" id="critical-level-input" class="form-control input-lg" min="1" placeholder="Inventory Critical Level" value="<?php echo ($criticalLevel != null ? $criticalLevel : 1); ?>" required>
            </div>
            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary"><span class="fas fa-check fa-fw"></span> Save Changes</button>
            </div>
        </form>
        <h3 class="page-header">Back-up Database</h3>
        <?php
            if($_SERVER['REQUEST_METHOD'] === 'POST') {
                if($_POST['what'] === 'backup_database') {
                    if(isset($_POST['db_filename']) && $_POST['db_filename'] !== '') {
                        $backupPath = '../db/' . date('Ymd_His') . '_' . input_escape_string($connection, $_POST['db_filename']) . '.sql';
                    } else {
                        $backupPath = '../db/' . date('Ymd_His') . '_database.sql';
                    }
                            
                    if(DB_PASSWORD !== '') {
                        $command = DB_DUMPER . ' --opt -h' . DB_HOSTNAME . ' -u' . DB_USERNAME . ' -p' . DB_PASSWORD . ' ' . DB_NAME . ' > ' . $backupPath;
                    } else {
                        $command = DB_DUMPER . ' --opt -h' . DB_HOSTNAME . ' -u' . DB_USERNAME . ' ' . DB_NAME . ' > ' . $backupPath;
                    }

                    $output = [];

                    exec($command, $output, $commandStatus);

                    switch($commandStatus) {
                        case 0:
        ?>
        <div class="alert alert-success">Database has been successfully exported.</div>
        <?php

                            break;
                        case 1:
        ?>
        <div class="alert alert-warning">There was a warning during the export of database.</div>
        <div class="well"><?php echo json_encode($output); ?></div>
        <?php

                            break;
                        case 2:
        ?>
        <div class="alert alert-danger">Error exporting database.</div>
        <?php

                            break;
                    }
                }
            }
        ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Filename</th>
                    <th width="10%">Action(s)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $backups = glob('../db/*.sql');

                    foreach($backups as $backup) {
                ?>
                <tr>
                    <td><?php echo basename($backup); ?></td>
                    <td class="text-center">
                        <a href="<?php echo $backup; ?>" download="<?php echo basename($backup); ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="Download File"><span class="fas fa-download fa-fw"></span></a>
                    </td>
                </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
        <form action="" method="POST">
            <div>
                <input type="hidden" name="what" value="backup_database">
            </div>
            <div class="form-group">
                <label for="db-filename-input">Filename:</label>
                <div class="input-group input-group-lg">
                    <input type="text" name="db_filename" id="db-filename-input" class="form-control" min="1" placeholder="Filename" value="<?php echo 'bitc_database'; ?>" required>
                    <span class="input-group-addon">.sql</span>
                </div>
                <span class="help-block">Note: Current timestamp will be prepended to the filename.</span>
            </div>
            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary"><span class="fas fa-check fa-fw"></span> Create Back-up</button>
            </div>
        </form>
    </div>
</div>
<?php
    include_once('../layouts/factory_management_end.php');
    include_once('../partials/modals.php');
    include_once('../layouts/footer.php');
?>
