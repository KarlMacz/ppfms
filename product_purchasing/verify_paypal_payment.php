<?php
    session_start();

    require_once('../backend/database.php');
    require_once('../backend/functions.php');

    if(!isset($_GET['status_code'])) {
        header('Location: recent_orders.php');

        exit();
    }

    include_once('../layouts/authorized_product_purchasing_header.php');
?>
<section id="verify-account-section" class="hero full-height">
    <div class="hero-content">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="form-group">
                        <a href="recent_orders.php" class="btn btn-default">Go Back</a>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header-icon">
                                <img src="img/logo.png">
                            </div>
                            <div class="card-header-title">Pay Through PayPal</div>
                        </div>
                        <div class="card-content">
                            <?php
                                $statusCode = (int) input_escape_string($connection, $_GET['status_code']);

                                $ppDeal = new PayPalDeal();

                                if($statusCode === 1) {
                                    $paymentID = $_GET['paymentId'];
                                    $payerID = $_GET['PayerID'];
                                    $trackingNumber = $_GET['tracking_number'];

                                    $result = $ppDeal->verify_transaction($paymentID, $payerID);

                                    if($result) {
                                        $date = date('Y-m-d');
                                        
                                        mysqli_query($connection, "UPDATE `orders` SET `amount_paid`=`amount_due`+`shipping_fee`, `date_paid`='$date' WHERE `tracking_number`='$trackingNumber'");
                                        
                            ?>
                            <div class="alert alert-success">Transaction has been verified.</div>
                            <?php
                                    } else {
                            ?>
                            <div class="alert alert-danger">An error occurred while trying to verify transaction.</div>
                            <?php
                                    }
                                } else {
                            ?>
                            <div class="alert alert-danger">Failed to verify paypal transaction.</div>
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
    include_once('../partials/modals.php');
    include_once('../layouts/footer.php');
?>
