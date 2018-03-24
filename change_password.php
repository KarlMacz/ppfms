<?php
    require_once('backend/database.php');
    require_once('backend/functions.php');

    if(!isset($_GET['ref'])) {
        header('Location: login.php');
    }

    include_once('layouts/header.php');
?>
<section id="change-password-section" class="hero full-height">
    <div class="hero-content">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-sm-offset-4">
                    <div class="form-group">
                        <a href="login.php" class="btn btn-default">Go Back</a>
                    </div>
                    <div class="card primary">
                        <div class="card-header">
                            <div class="card-header-icon">
                                <img src="img/logo.png">
                            </div>
                            <div class="card-header-title">Change Password</div>
                        </div>
                        <div class="card-content">
                            <form id="change-password-form">
                                <div>
                                    <input type="hidden" name="request_code" value="<?php echo (isset($_GET['ref']) ? $_GET['ref'] : ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="new-password-input">New Password:</label>
                                    <input type="password" name="new_password" id="new-password-input" class="form-control" placeholder="New Password" required autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="confirm-new-password-input">Confirm New Password:</label>
                                    <input type="password" name="confirm_new_password" id="confirm-new-password-input" class="form-control" placeholder="Confirm New Password" required>
                                </div>
                                <di class="text-right">
                                    <button class="btn btn-primary"><span class="fas fa-edit fa-fw"></span> Change</button>
                                </di>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
    include_once('partials/modals.php');
?>
<script src="js/custom/change_password.js"></script>
<?php
    include_once('layouts/footer.php');
?>
