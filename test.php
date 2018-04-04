<?php
    require_once('backend/database.php');
    require_once('backend/functions.php');

    function account_seeder() {
        global $connection;
        global $today;

        $password = hash('sha256', 'admin');
        mysqli_query($connection, "INSERT INTO `accounts` (`username`, `password`, `email`, `type`, `is_verified`, `created_at`)
            VALUES ('admin', '$password', 'admin@bitc.com', 'Administrator', 1, '$today')");

        if(mysqli_affected_rows($connection)) {
            $accountID = mysqli_insert_id($connection);
            $query = mysqli_query($connection, "INSERT INTO `users` (`account_id`, `first_name`, `last_name`, `gender`, `birth_date`, `address`)
                VALUES ('$accountID', '', '', 'Male', '1990-01-01', 'Kampri Bldg, 2254 Don Chino Roces Avenue, Makati City, Metro Manila')");
        }

        $password = hash('sha256', 'jinjin');
        mysqli_query($connection, "INSERT INTO `accounts` (`username`, `password`, `email`, `is_verified`, `created_at`)
            VALUES ('karlmacz', '$password', 'karljarren0308@gmail.com', 1, '$today')");

        if(mysqli_affected_rows($connection)) {
            $accountID = mysqli_insert_id($connection);
            $query = mysqli_query($connection, "INSERT INTO `users` (`account_id`, `first_name`, `last_name`, `gender`, `birth_date`, `address`)
                VALUES ('$accountID', 'Karl', ' Macadangdang', 'Male', '1996-01-01', 'Sampaloc, Manila')");
        }
    }

    function supplier_seeder() {
        global $connection;

        mysqli_query($connection, "INSERT INTO `suppliers` (`name`, `description`)
            VALUES ('GreenCross Incorporated', 'Alcohol Namba Wan!!!')");
    }

    function product_seeder() {
        global $connection;
        global $today;

        $productCode = generate_product_code($connection);
        mysqli_query($connection, "INSERT INTO `products` (`product_code`, `name`, `description`, `category`, `type`, `quantity_available`, `item_price`, `created_at`)
            VALUES ('$productCode', 'GreenCross Isopropyl Alcohol [250mL]', '250mL GreenCross Isopropyl Alcohol with Moisturizer', 'Hand Sanitizer', 'Hands/Nails', '100', '30', '$today')");

        if(mysqli_affected_rows($connection)) {
            $productID = mysqli_insert_id($connection);
            mysqli_query($connection, "INSERT INTO `inventories` (`product_id`, `supplier_id`, `boxes_arrived`, `boxes_in_stock`, `date_ordered`, `created_at`)
                VALUES ('$productID', '1', '5', '4', '$today', '$today')");
        }
    }

    if(isset($_GET['command'])) {
        switch($_GET['command']) {
            case 'seed':
                if(isset($_GET['command'])) {
                    switch($_GET['what']) {
                        case 'accounts':
                            account_seeder();

                            break;
                        case 'products':
                            product_seeder();

                            break;
                        case 'suppliers':
                            supplier_seeder();

                            break;
                        default:
                            break;
                    }
                }

                break;
            default:
                break;
        }
    }
?>
