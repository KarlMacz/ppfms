<?php
    define('MY_URL', 'http://localhost/ppfms');
    define('COMPANY_NAME', 'Bhagi\'s International Trading Corporation');

    define('PAYPAL_CLIENT_ID', 'AQio7ikbXriIo6k3-58XG4Up5QPUKOF3SWDL_zjvlvCt_SPKUJJrZMoX0cPxwPovxsoKvYxV0WIyxypP');
    define('PAYPAL_SECRET', 'ENRbRAvl1yQTst2JmF5Ez2UJZC0GsB2v-F-WLVZhtDFuYyUp6tDTmxgdfr_FFwOhq2NtOo8_X8MvYMe5');

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
    use PayPal\Api\PaymentDetail as PayPalPaymentDetail;
    use PayPal\Api\PaymentExecution as PayPalPaymentExecution;
    use PayPal\Api\Address as PayPalAddress;
    use PayPal\Api\BillingInfo as PayPalBillingInfo;
    use PayPal\Api\Cost as PayPalCost;
    use PayPal\Api\Currency as PayPalCurrency;
    use PayPal\Api\Invoice as PayPalInvoice;
    use PayPal\Api\InvoiceAddress as PayPalInvoiceAddress;
    use PayPal\Api\InvoiceItem as PayPalInvoiceItem;
    use PayPal\Api\MerchantInfo as PayPalMerchantInfo;
    use PayPal\Api\PaymentTerm as PayPalPaymentTerm;
    use PayPal\Api\Phone as PayPalPhone;
    use PayPal\Api\ShippingInfo as PayPalShippingInfo;
    use PayPal\Api\Tax as PayPalTax;

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

        function make_transaction($description, $paypalItems, $shippingFee = 0.00, $returnUrl, $cancelUrl) {
            $payer = new PayPalPayer();
            $payer->setPaymentMethod('paypal');

            $items = [];
            $subtotal = 0;

            foreach($paypalItems as $paypalItem) {
                $item = new PayPalItem();

                $item->setName($paypalItem['name'])
                    ->setCurrency('PHP')
                    ->setQuantity($paypalItem['quantity'])
                    ->setPrice($paypalItem['price'] / $paypalItem['quantity']);

                $subtotal += $paypalItem['price'];
                $items[] = $item;
            }

            $itemList = new PayPalItemList();
            $itemList->setItems($items);

            $details = new PayPalDetails();
            $details->setShipping($shippingFee)
                ->setSubtotal($subtotal);

            $amount = new PayPalAmount();
            $amount->setCurrency('PHP')
                ->setTotal($subtotal + $shippingFee)
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

                return true;
            } catch(Exception $ex) {
                return false;
            }
        }
    }

    /*
    * PayPalDeal Class
    */
    class PayPalVoiceOut {
        private $invoice;
        private $billing;
        private $shipping;
        private $items;
        private $paypalApiContext;

        function __construct() {
            $this->paypalApiContext = new PayPalApiContext(
                new PayPalOAuthTokenCredential(PAYPAL_CLIENT_ID, PAYPAL_SECRET)
            );
            $this->items = [];
        }

        function initialize_invoice() {
            $this->invoice = new PayPalInvoice();

            $this->invoice->setMerchantInfo(new PayPalMerchantInfo())
                ->setBillingInfo([new PayPalBillingInfo()])
                ->setNote('Order Invoice ' . date('F d, Y'))
                ->setPaymentTerm(new PayPalPaymentTerm())
                ->setShippingInfo(new PayPalShippingInfo());

            $this->invoice->getMerchantInfo()
                ->setEmail('lin.baywrigth069@bitc-ppfms.com')
                ->setBusinessName(COMPANY_NAME)
                ->setWebsite('https://bitccosmetics.com')
                ->setPhone(new PayPalPhone())
                ->setAddress(new PayPalAddress());

            $this->invoice->getMerchantInfo()->getPhone()
                ->setCountryCode('63')
                ->setNationalNumber("8942028");

            $this->invoice->getMerchantInfo()->getAddress()
                ->setLine1("Kampri Bldg, 2254 Don Chino Roces Avenue")
                ->setCity("Makati")
                ->setState("Metro Manila")
                ->setPostalCode("1233")
                ->setCountryCode("PH");
        }

        function retrieve_existing_invoice($invoiceID) {
            $this->invoice = PayPalInvoice::get($invoiceID, $this->paypalApiContext);
        }

        function parse_address($address) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyBm8OIKZ7OtOykRclmFgfF9wRboRgnFGN8&address=' . urlencode('Sampaloc, Manila'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $response = json_decode(curl_exec($ch), true);

            if($response['status'] !== 'OK') {
                return null;
            }

            return $response;
        }

        function set_billing_info($firstName, $lastName, $email, $line, $city, $state, $postal, $countryCode) {
            $this->billing = $this->invoice->getBillingInfo();

            $this->billing[0]->setEmail($email)
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setBusinessName('Not applicable')
                ->setPhone(new PayPalPhone())
                ->setAddress(new PayPalAddress());

            $this->billing[0]->getPhone()
                ->setCountryCode('63')
                ->setNationalNumber('9068563348');

            $this->billing[0]->getAddress()
                ->setLine1($line)
                ->setCity($city)
                ->setState($state)
                ->setPostalCode($postal)
                ->setCountryCode($countryCode);
        }

        function set_shipping_info($firstName, $lastName, $email, $line, $city, $state, $postal, $countryCode) {
            $this->shipping = $this->invoice->getShippingInfo();

            $this->shipping->setEmail($email)
                ->setFirstName($firstName)
                ->setLastName($lastName)
                ->setBusinessName('Not applicable')
                ->setPhone(new PayPalPhone())
                ->setAddress(new PayPalAddress());

            $this->shipping->getPhone()
                ->setCountryCode('63')
                ->setNationalNumber('9068563348');

            $this->shipping->getAddress()
                ->setLine1($line)
                ->setCity($city)
                ->setState($state)
                ->setPostalCode($postal)
                ->setCountryCode($countryCode);
        }

        function set_item_list($itemList) {
            $this->items = [];

            for($i = 0; $i < count($itemList); $i++) {
                $this->items[$i] = new PayPalInvoiceItem();

                $this->items[$i]->setName($itemList[$i]['name'])
                    ->setQuantity($itemList[$i]['quantity'])
                    ->setUnitPrice(new PayPalCurrency());

                $this->items[$i]->getUnitPrice()
                    ->setCurrency('PHP')
                    ->setValue($itemList[$i]['price']);

                $tax = new PayPalTax();
                $tax->setPercent(1)->setName("Local Tax on Sutures");

                $this->items[$i]->setTax($tax);
            }

            $this->invoice->setItems($this->items);
        }

        function create_invoice() {
            $this->invoice->getPaymentTerm()
                ->setTermType('NET_45');

            $this->invoice->setLogoUrl('https://bitccosmetics.com/img/logo.png');

            try {
                $this->invoice->create($this->paypalApiContext);
            } catch (Exception $ex) {
                die('<pre>' . $ex . '</pre>');

                return null;
            }

            return true;
        }

        function send_invoice() {
            try {
                $sendStatus = $this->invoice->send($this->paypalApiContext);
            } catch (Exception $ex) {
                die('<pre>' . $ex . '</pre>');

                return null;
            }
        }

        function get_invoice() {
            try {
                $returnedInvoice = PayPalInvoice::get($this->invoice->getId(), $this->paypalApiContext);
            } catch (Exception $ex) {
                die('<pre>' . $ex . '</pre>');

                return null;
            }

            return $returnedInvoice;
        }

        function record_payment() {
            $paymentDetail = new PayPalPaymentDetail('{
                "method": "CASH",
                "date": "' . date('Y-m-d H:i:s T') . '",
                "note": "Payment received."
            }');

            try {
                $recordStatus = $this->invoice->recordPayment($paymentDetail, $this->paypalApiContext);
            } catch (Exception $ex) {
                die('<pre>' . $ex . '</pre>');

                return null;
            }

            return $recordStatus;
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
        $headers[] = 'From: Bhagi\'s International Trading Corporation <noreply@bitccosmetics.com>';

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

        if(explode('|', $validation)[0] === 'date_before') {
            $beforeDate = explode('|', $validation)[1];

            if(substr($input, 0, 4) % 4 == 0) {
                $regex = '/^([12]([0-9]{3}))-((02-(0[1-9]|1[0-9]|2[0-9]))|((04|06|09|11)-(0[1-9]|[12][0-9]|30))|((01|03|05|07|08|10|12)-(0[1-9]|[12][0-9]|3[01])))$/';
            } else {
                $regex = '/^([12]([0-9]{3}))-((02-(0[1-9]|1[0-9]|2[0-8]))|((04|06|09|11)-(0[1-9]|[12][0-9]|30))|((01|03|05|07|08|10|12)-(0[1-9]|[12][0-9]|3[01])))$/';
            }

            if($regex !== '') {
                if($input !== '' && !preg_match($regex, $input)) {
                    return 'Input should be a valid date.';
                } else {
                    if(strtotime($input) >= strtotime($beforeDate)) {
                        return 'Input should be a valid date before ' . date('F d, Y', strtotime($beforeDate)) . '.';
                    }
                }
            } else {
                return 'Invalid validation type.';
            }
        } else if($validation === 'email') {
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

                    $errorMessage = 'Input should be a valid date.';

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
