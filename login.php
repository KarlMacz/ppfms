<?php
    session_start();

    require_once('backend/database.php');
    require_once('backend/functions.php');

    if(isset($_SESSION['user_id'])) {
        switch($_SESSION['type']) {
            case 'Administrator':
                header('Location: factory_management/index.php');

                exit();

                break;
            case 'Client':
                header('Location: product_purchasing/index.php');

                exit();
                
                break;
            default:
                session_destroy();

                break;
        }
    }

    include_once('layouts/header.php');
?>
<section id="login-section" class="hero full-height">
    <div class="hero-content">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-sm-offset-4">
                    <div class="form-group">
                        <a href="index.php" class="btn btn-default">Go Back</a>
                    </div>
                    <div class="card primary">
                        <div class="card-header">
                            <div class="card-header-icon">
                                <img src="img/logo.png">
                            </div>
                            <div class="card-header-title">Login your Account</div>
                        </div>
                        <div class="card-content">
                            <form id="login-form">
                                <div class="form-group">
                                    <label for="username-input">Username:</label>
                                    <input type="text" name="username" id="username-input" class="form-control" placeholder="Username" required autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="password-input">Password:</label>
                                    <input type="password" name="password" id="password-input" class="form-control" placeholder="Password" required>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary"><span class="fas fa-sign-in-alt fa-fw"></span> Login</button>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <a href="forgot_password.php" class="card-footer-item">Forgot Password</a>
                            <a href="register.php" class="card-footer-item">Register</a>
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
<script src="js/custom/login.js"></script>
<?php
    include_once('layouts/footer.php');
?>
