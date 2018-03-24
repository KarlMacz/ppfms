<?php
    require_once('backend/database.php');
    require_once('backend/functions.php');

    include_once('layouts/header.php');
?>
<section id="registration-section" class="hero inline">
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
                            <div class="card-header-title">Register an Account</div>
                        </div>
                        <div class="card-content">
                            <form id="register-form">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="username-input">Username:</label>
                                            <input type="text" name="username" id="username-input" class="form-control" placeholder="Username" required autofocus>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="email-input">E-mail Address:</label>
                                            <input type="email" name="email" id="email-input" class="form-control" placeholder="E-mail Address" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="password-input">Password:</label>
                                            <div class="control">
                                                <input type="password" name="password" id="password-input" class="form-control" placeholder="Password" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="confirm-password-input">Confirm Password:</label>
                                            <div class="control">
                                                <input type="password" name="confirm_password" id="confirm-password-input" class="form-control" placeholder="Confirm Password" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="first-name-input">First Name:</label>
                                            <div class="control">
                                                <input type="text" name="first_name" id="first-name-input" class="form-control" placeholder="First Name" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="middle-name-input">Middle Name:</label>
                                            <div class="control">
                                                <input type="text" name="middle_name" id="middle-name-input" class="form-control" placeholder="Middle Name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="last-name-input">Last Name:</label>
                                            <div class="control">
                                                <input type="text" name="last_name" id="last-name-input" class="form-control" placeholder="Last Name" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="gender-input">Gender:</label>
                                            <select name="gender" id="gender-input" class="form-control" required>
                                                <option value="" selected disabled>Select an option...</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="birth-date-input">Birth Date:</label>
                                            <input type="date" name="birth_date" id="birth-date-input" class="form-control" placeholder="Birth Date" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address-input">Address:</label>
                                    <input type="text" name="address" id="address-input" class="form-control" placeholder="Address" required>
                                </div>
                                <hr>
                                <div class="g-recaptcha" data-sitekey="6LdIJkoUAAAAAEAq-7Oq7G57vKKwXkEeAcajyQX9"></div>
                                <div class="text-right">
                                    <button class="btn btn-primary"><span class="fas fa-check fa-fw"></span> Register</button>
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
<script src="js/custom/register.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<?php
    include_once('layouts/footer.php');
?>
