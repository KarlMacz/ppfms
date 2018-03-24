<?php
    require_once('backend/database.php');
    require_once('backend/functions.php');

    if(!isset($_GET['ref'])) {
        header('Location: login.php');
    }

    include_once('layouts/header.php');
?>
<section class="hero full-height">
    <div class="hero-content">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="form-group">
                        <a href="login.php" class="btn btn-default">Go Back</a>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-icon">
                                <img src="img/logo.png">
                            </div>
                            <div class="card-header-title">Account Verification</div>
                        </div>
                        <div class="card-content">
                            <?php
                                $verificationCode = input_escape_string($connection, $_GET['ref']);

                                $query = mysqli_query($connection, "SELECT * FROM `accounts` WHERE `verification_code`='$verificationCode'");

                                if(mysqli_num_rows($query) === 1) {
                                    $row = mysqli_fetch_assoc($query);

                                    $accountID = $row['id'];

                                    $accountQuery = mysqli_query($connection, "UPDATE `accounts` SET `is_verified`='1', `verification_code`=NULL WHERE `id`='$accountID'");

                                    if(mysqli_affected_rows($connection) === 1) {
                            ?>
                            <div class="alert alert-success"><span class="fas fa-check fa-fw"></span> Your account has been verified. You may now log in your account.</div>
                            <?php
                                    } else {
                            ?>
                            <div class="alert alert-danger"><span class="fas fa-times fa-fw"></span> Failed to verify your account. Please refresh the page.</div>
                            <?php
                                    }
                                } else {
                            ?>
                            <div class="alert alert-danger"><span class="fas fa-times fa-fw"></span> Account with the same verification code doesn't exist.</div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
    include_once('partials/modals.php');
    include_once('layouts/footer.php');
?>
