<?php
    define('MY_URL', 'http://localhost/ppfms');
    define('COMPANY_NAME', 'Bhagi\'s International Trading Corporation');

    define('PAYPAL_CLIENT_ID', 'AelPbilr--EfyAll4rh_rrKRH1tpSSIVbIVGyuv8gaBESxJUhfril1tTlO7BhJ4hIXOa1cn4MbwEVB9a');
    define('PAYPAL_SECRET', 'EIyXdWK9GFKBssNxet9Ou2hXnGZ26JXamaj0-W1SXOe0ZebCCmXMtMhXe_V0sg0PBT1PTrfcZDTvAyXl');\

    define('RECAPTCHA_SITE_KEY', '6LdIJkoUAAAAAEAq-7Oq7G57vKKwXkEeAcajyQX9');
    define('RECAPTCHA_SECRET_KEY', '6LdIJkoUAAAAAF8-PxFFs3WbYJBID1PYQi50iucT');

    require_once(__DIR__ . '/../vendor/autoload.php');

    use PayPal\Rest\ApiContext as PayPalApiContext;
    use PayPal\Auth\OAuthTokenCredential as PayPalOAuthTokenCredential;
    use PayPal\Api\Payer as PayPalPayer;
    use PayPal\Api\Item as PayPalItem;
    use PayPal\Api\ItemList as PayPalItemList;
    use PayPal\Api\Details as PayPalDetails;
    use PayPal\Api\Amount as PayPalAmount;
    use PayPal\Api\Transaction as PayPalTransaction;
    use PayPal\Api\RedirectUrls as PayPalRedirectUrls;
    use PayPal\Api\Payment as PayPalPayment;
    use PayPal\Api\PaymentExecution as PayPalPaymentExecution;

    use Respect\Validation\Validator;
    use Respect\Validation\Exceptions\ValidationException;

    /*
    * PayPalDeal Class
    */
    class PayPalDeal {
        private $paypalApiContext;

        function __construct() {
            $this->paypalApiContext = new PayPalApiContext(
                new PayPalOAuthTokenCredential(PAYPAL_CLIENT_ID, PAYPAL_SECRET)
            );
        }

        function make_transaction($description, $paypalItems, $returnUrl, $cancelUrl) {
            $payer = new PayPalPayer();
            $payer->setPaymentMethod('paypal');

            $items = [];
            $subtotal = 0;

            foreach($paypalItems as $paypalItem) {
                $item = new PayPalItem();

                $item->setName($paypalItem['name'])
                    ->setCurrency('PHP')
                    ->setQuantity($paypalItem['quantity'])
                    ->setPrice($paypalItem['price']);

                $subtotal += $paypalItem['price'] * $paypalItem['quantity'];
                $items[] = $item;
            }

            $itemList = new PayPalItemList();
            $itemList->setItems($items);

            $details = new PayPalDetails();
            $details->setShipping(0.00)
                ->setSubtotal($subtotal);

            $amount = new PayPalAmount();
            $amount->setCurrency('PHP')
                ->setTotal($subtotal)
                ->setDetails($details);

            $transaction = new PayPalTransaction();
            $transaction->setAmount($amount)
                ->setItemList($itemList)
                ->setDescription($description)
                ->setInvoiceNumber(uniqid());

            $redirectUrls = new PayPalRedirectUrls();
            $redirectUrls->setReturnUrl(MY_URL . $returnUrl)
                ->setCancelUrl(MY_URL . $cancelUrl);

            $payment = new PayPalPayment();
            $payment->setIntent('sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions([$transaction]);

            try {
                $payment->create($this->paypalApiContext);
            } catch(PayPal\Exception\PayPalConnectionException $ex) {
                echo $ex->getCode();
                echo $ex->getData();
            } catch (Exception $ex) {
                die($ex);
            }

            return $payment->getApprovalLink();
        }

        function verify_transaction($paymentID, $payerID) { // $_GET['paymentId']; $_GET['PayerID'];
            $payment = PayPalPayment::get($paymentID, $this->paypalApiContext);

            $execute = new PayPalPaymentExecution();
            $execute->setPayerId($payerID);

            try {
                $result = $payment->execute($execute, $this->paypalApiContext);
            } catch(Exception $ex) {
                die($ex);
            }
        }
    }

    /*
    * Validatr Class
    */
    class Validatr {
        private $validationErrors = [];

        function validate_input($type, $name, $input) {
            global $validationErrors;

            $key = strtolower(str_replace(' ', '_', $name));

            try {
                switch($type) {
                    case 'alpha':
                        $validatr = Validator::alpha();

                        break;
                    case 'alpha_nowhitespace':
                        $validatr = Validator::alpha()->noWhitespace();

                        break;
                    case 'alphanumeric':
                        $validatr = Validator::alnum();

                        break;
                    case 'alphanumeric_nowhitespace':
                        $validatr = Validator::alnum()->noWhitespace();

                        break;
                    case 'date':
                        $validatr = Validator::date('Y-m-d');
                        
                        break;
                    case 'datetime':
                        $validatr = Validator::date('Y-m-d H:i:s');
                        
                        break;
                    case 'digit':
                        $validatr = Validator::digit();

                        break;
                    case 'email':
                        $validatr = Validator::email();

                        break;
                    case 'phone':
                        $validatr = Validator::phone();

                        break;
                    case 'time':
                        $validatr = Validator::date('H:i:s');
                        
                        break;
                }

                $validatr->setName($name)->check($input);
            } catch(ValidationException $exception) {
                $validationErrors[$key] = $exception->getMainMessage();
            }

            return $input;
        }

        function validate_inputs($inputArray, $typeArray) {
            global $validationErrors;

            foreach($inputArray as $key => $value) {
                try {
                    switch($typeArray[$key]['type']) {
                        case 'alpha':
                            $validatr = Validator::alpha();

                            break;
                        case 'alpha_nowhitespace':
                            $validatr = Validator::alpha()->noWhitespace();

                            break;
                        case 'alphanumeric':
                            $validatr = Validator::alnum();

                            break;
                        case 'alphanumeric_nowhitespace':
                            $validatr = Validator::alnum()->noWhitespace();

                            break;
                        case 'date':
                            $validatr = Validator::date('Y-m-d');
                            
                            break;
                        case 'datetime':
                            $validatr = Validator::date('Y-m-d H:i:s');
                            
                            break;
                        case 'digit':
                            $validatr = Validator::digit();

                            break;
                        case 'email':
                            $validatr = Validator::email();

                            break;
                        case 'phone':
                            $validatr = Validator::phone();

                            break;
                        case 'time':
                            $validatr = Validator::date('H:i:s');
                            
                            break;
                    }

                    $validatr->setName($typeArray[$key]['name'])->check($value);
                } catch(ValidationException $exception) {
                    $validationErrors[$key] = $exception->getMainMessage();
                }
            }

            return $inputArray;
        }

        function prompt_errors() {
            global $validationErrors;

            if(count($validationErrors) > 0) {
                echo json_encode([
                    'status' => 'Error',
                    'type' => 'validation',
                    'message' => count($validationErrors) . ' validation error(s) occurred.',
                    'errors' => $validationErrors
                ]);

                $validationErrors = [];

                exit();
            }
        }
    }

    /*
    * Other Custom Functions
    */
    function send_email($email, $subject, $content, $name = '') {
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';
        $headers[] = ($name !== '' ? 'To: ' . $name . ' <' . $email . '>' : 'To: <' . $email . '>');
        $headers[] = 'From: Bhagi\'s International Trading Corporation';

        @mail($email, $subject, $content, implode("\r\n", $headers));
    }

    function generate_product_code($connection) {
        $str = '01234aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ56789';
        $code = '';
        $isOk = false;

        do {
            $code = '';

            for($i = 0; $i < 10; $i++) {
                $code .= $str[mt_rand(0, strlen($str) - 1)];
            }

            $query = mysqli_query($connection, "SELECT * FROM `products` WHERE `product_code`='$code'");

            if(mysqli_num_rows($query) === 0) {
                $isOk = true;
            }
        } while(!$isOk);

        return $code;
    }

    function generate_verification_code($connection) {
        $str = '01234aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ56789';
        $code = '';
        $isOk = false;

        do {
            $code = '';

            for($i = 0; $i < 64; $i++) {
                $code .= $str[mt_rand(0, strlen($str) - 1)];
            }

            $query = mysqli_query($connection, "SELECT * FROM `accounts` WHERE `verification_code`='$code'");

            if(mysqli_num_rows($query) === 0) {
                $isOk = true;
            }
        } while(!$isOk);

        return $code;
    }

    function generate_change_password_code($connection) {
        $str = '01234AbcdEfghIjklmnOpqrstUvwxyz56789';
        $code = '';
        $isOk = false;

        do {
            $code = '';

            for($i = 0; $i < 32; $i++) {
                $code .= $str[mt_rand(0, strlen($str) - 1)];
            }

            $query = mysqli_query($connection, "SELECT * FROM `accounts` WHERE `change_password_code`='$code'");

            if(mysqli_num_rows($query) === 0) {
                $isOk = true;
            }
        } while(!$isOk);

        return $code;
    }

    function generate_tracking_number($connection) {
        $code = '';
        $isOk = false;

        do {
            $code = '';

            for($i = 0; $i < 10; $i++) {
                $code .= mt_rand(0, 9);
            }

            $query = mysqli_query($connection, "SELECT * FROM `orders` WHERE `tracking_number`='$code'");

            if(mysqli_num_rows($query) === 0) {
                $isOk = true;
            }
        } while(!$isOk);

        return $code;
    }

    function generate_batch_number($connection) {
        $code = '';
        $isOk = false;

        do {
            $code = '';

            for($i = 0; $i < 10; $i++) {
                $code .= mt_rand(0, 9);
            }

            $query = mysqli_query($connection, "SELECT * FROM `batches` WHERE `batch_number`='$code'");

            if(mysqli_num_rows($query) === 0) {
                $isOk = true;
            }
        } while(!$isOk);

        return $code;
    }

    function input_escape_string($connection, $input) {
        return mysqli_real_escape_string($connection, $input);
    }

    function validate_input($input, $validation) {
        $validation = strtolower($validation);

        if($validation === 'email') {
            if(!filter_var($input, FILTER_VALIDATE_EMAIL)) {
                return 'Input should be a valid email address.';
            }
        } else {
            $regex = '';

            switch($validation) {
                case 'date':
                    if(substr($input, 0, 4) % 4 == 0) {
                        $regex = '/^([12]([0-9]{3}))-((02-(0[1-9]|1[0-9]|2[0-9]))|((04|06|09|11)-(0[1-9]|[12][0-9]|30))|((01|03|05|07|08|10|12)-(0[1-9]|[12][0-9]|3[01])))$/';
                    } else {
                        $regex = '/^([12]([0-9]{3}))-((02-(0[1-9]|1[0-9]|2[0-8]))|((04|06|09|11)-(0[1-9]|[12][0-9]|30))|((01|03|05|07|08|10|12)-(0[1-9]|[12][0-9]|3[01])))$/';
                    }

                    $errorMessage = 'Input should be a valid date';

                    break;
                case 'number':
                    $regex = '/^([0-9])+$/';

                    $errorMessage = 'Input should be a number.';

                    break;
                case 'decimal':
                    $regex = '/^([0-9])+(\.?([0-9])+)$/';

                    $errorMessage = 'Input should be a decimal number.';

                    break;
                case 'mobile':
                    $regex = '/^(09|(\+)?639)[0-9]{9}$/';

                    $errorMessage = 'Input should be a valid mobile number.';

                    break;
                case 'name':
                    $regex = '/^([a-zA-Z\ \-])+$/';

                    $errorMessage = 'Input should be a valid name.';

                    break;
                case 'alphanumeric':
                    $regex = '/^([0-9a-zA-Z])+$/';

                    $errorMessage = 'Input should be alphanumeric.';

                    break;
            }

            if($regex !== '') {
                if($input !== '' && !preg_match($regex, $input)) {
                    return $errorMessage;
                }
            } else {
                return 'Invalid validation type.';
            }
        }

        return true;
    }
?>
