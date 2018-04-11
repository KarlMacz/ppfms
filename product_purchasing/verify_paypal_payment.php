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
                                <img src="../img/logo.png">
                            </div>
                            <div class="card-header-title">Pay Through PayPal</div>
                        </div>
                        <div class="card-content">
                            <?php
                                $statusCode = (int) input_escape_string($connection, $_GET['status_code']);

                                $ppDeal = new PayPalDeal();
                                $ppVoiceOut = new PayPalVoiceOut();

                                if($statusCode === 1) {
                                    $paymentID = $_GET['paymentId'];
                                    $payerID = $_GET['PayerID'];
                                    $trackingNumber = $_GET['tracking_number'];

                                    $result = $ppDeal->verify_transaction($paymentID, $payerID);

                                    $query = mysqli_query($connection, "SELECT *,
                                            `orders`.`id` AS `ord_id`
                                        FROM `orders`
                                        INNER JOIN `accounts`
                                            ON `orders`.`account_id`=`accounts`.`id`
                                        INNER JOIN `users`
                                            ON `accounts`.`id`=`users`.`account_id`
                                        INNER JOIN `billing_addresses`
                                            ON `orders`.`billing_address_id`=`billing_addresses`.`id`
                                        INNER JOIN `shipping_addresses`
                                            ON `orders`.`shipping_address_id`=`shipping_addresses`.`id`
                                        WHERE `orders`.`tracking_number`='$trackingNumber'");
                                    $row = mysqli_fetch_assoc($query);
                                    $orderID = $row['ord_id'];

                                    if($row['middle_name'] != null) {
                                        $fullName = $row['first_name'] . ' ' . substr($row['middle_name'], 0, 1) . '. ' . $row['last_name'];
                                    } else {
                                        $fullName = $row['first_name'] . ' ' . $row['last_name'];
                                    }

                                    $query2 = mysqli_query($connection, "SELECT * FROM `order_items`
                                        INNER JOIN `products`
                                            ON `order_items`.`product_id`=`products`.`id`
                                        WHERE `order_items`.`order_id`='$orderID'");

                                    $items = [];

                                    while($row2 = mysqli_fetch_assoc($query2)) {
                                        $items[] = [
                                            'name' => $row2['name'],
                                            'quantity' => $row2['quantity'],
                                            'price' => ((double) $row2['total_amount'] / (double) $row2['quantity']),
                                        ];
                                    }

                                    $ppVoiceOut = new PayPalVoiceOut();

                                    $ppVoiceOut->initialize_invoice();

                                    $bAddress = $ppVoiceOut->parse_address($row['billing_address']);
                                    $line = '';
                                    $locality = '';
                                    $administrativeAreaLevel = '';
                                    $country = '';

                                    foreach($bAddress as $ba) {
                                        if($i = array_search('locality', $ba['types'])) {
                                            $locality .= $ba['long_name'];
                                        } else if($i = array_search('administrative_area_level_1', $ba['types'])) {
                                            $administrativeAreaLevel .= $ba['long_name'];
                                        } else if($i = array_search('country', $ba['types'])) {
                                            $country .= $ba['short_name'];
                                        } else {
                                            $line .= $ba['long_name'];
                                        }
                                    }

                                    $ppVoiceOut->set_billing_info($row['first_name'], $row['last_name'], $row['email'], $line, $locality, $administrativeAreaLevel, '', $country);

                                    $sAddress = $ppVoiceOut->parse_address($row['shipping_address']);
                                    $line = '';
                                    $locality = '';
                                    $administrativeAreaLevel = '';
                                    $country = '';

                                    foreach($sAddress as $sa) {
                                        if($i = array_search('locality', $sa['types'])) {
                                            $locality .= $sa['long_name'];
                                        } else if($i = array_search('administrative_area_level_1', $sa['types'])) {
                                            $administrativeAreaLevel .= $sa['long_name'];
                                        } else if($i = array_search('country', $sa['types'])) {
                                            $country .= $sa['short_name'];
                                        } else {
                                            $line .= $sa['long_name'];
                                        }
                                    }

                                    $ppVoiceOut->set_shipping_info($row['first_name'], $row['last_name'], $row['email'], $line, $locality, $administrativeAreaLevel, '', $country);
                                    $ppVoiceOut->set_item_list($items);

                                    if($result) {
                                        $date = date('Y-m-d');

                                        $ppVoiceOut->create_invoice();
                                        $ppVoiceOut->send_invoice();

                                        $invoiceID = $ppVoiceOut->send_invoice();
                                        $invoiceLink = 'https://www.sandbox.paypal.com/invoice/payerView/details/' . $invoiceID;

                                        send_email($row['email'], 'Order #' . $trackingNumber . ' Invoice Details', '<!DOCTYPE html>
                                            <html lang="en">
                                            <head>
                                                <meta charset="UTF-8">
                                                <title>' . COMPANY_NAME . '</title>
                                                <style>
                                                    body { font-family: BlinkMacSystemFont, -apple-system, "Segoe UI", "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", "Helvetica", "Arial", sans-serif; }
                                                    h1, h2, h3, h4, h5, h6 { margin 0 0 10px 0; }
                                                    p { margin: 0; }
                                                    .content p { margin: 10px 0; }
                                                </style>
                                            </head>
                                            <body>
                                                <h3>Hi ' . $row['first_name'] . ',</h3>
                                                <div class="content">
                                                    <p>Thank you for ordering at <a href="' . MY_URL . '" target="_blank">' . COMPANY_NAME . '</a>.</p>
                                                    <p>For your invoice details, please visit: <a href="' . $invoiceLink . '">' . $invoiceLink . '</a>. The said invoice is generated by PayPal.</p>
                                                </div>
                                            </body>
                                            </html>', $fullName);
                                        
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
