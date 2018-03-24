<?php
    require_once('backend/database.php');
    require_once('backend/functions.php');

    include_once('layouts/header.php');
?>
<section id="forgot-password-section" class="hero full-height">
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
                            <div class="card-header-title">Forgot Password</div>
                        </div>
                        <div class="card-content">
                            <form id="forgot-password-form">
                                <div class="form-group">
                                    <label for="email-input">E-mail Address:</label>
                                    <input type="email" name="email" id="email-input" class="form-control" placeholder="E-mail Address" required autofocus>
                                </div>
                                <div class="text-right">
                                    <button class="btn btn-primary"><span class="fas fa-paper-plane fa-fw"></span> Send</button>
                                </div>
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
<script src="js/custom/forgot_password.js"></script>
<?php
    include_once('layouts/footer.php')
?>
