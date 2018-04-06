<?php
    require_once('../../vendor/autoload.php');
    require_once('../database.php');
    require_once('../functions.php');

    use Dompdf\Dompdf;
    use Dompdf\Options;

    ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo COMPANY_NAME; ?></title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <style>
        hr {
            margin: 5px 0;
        }

        table.vtop td {
            vertical-align: top;
        }

        .mainbody {
            font-family: 'Helvetica';
            font-size: 15px;
            padding: 15px;
        }

        .header {
            border-bottom: 2px solid #ccc;
            display: inline-block;
            font-size: 0.75em;
            width: 100%;
        }

        .header .logo {
            display: inline-block;
            vertical-align: middle;
            padding: 10px;
            height: 55px;
        }

        .header .logo > img {
            display: inline-block;
            vertical-align: middle;
            height: 100%;
        }

        .header .texts {
            display: inline-block;
            vertical-align: middle;
        }

        .header .texts > .title {
            font-size: 2.5em;
            font-weight: bold;
        }

        .header .texts > .subtitle {
            font-size: 1.5em;
        }

        .footer {
            border-top: 2px solid #ccc;
            font-size: 0.75em;
        }

        .content {
            padding: 10px;
        }

        .content > .title {
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }

        .liner {
            position: relative;
            margin-bottom: 10px;
        }

        .no-padding {
            padding: 0;
        }

        .no-margin {
            margin: 0;
        }

        .fnt-sm {
            font-size: 0.75em;
        }

        .fnt-lg {
            font-size: 1.25em;
        }
    </style>
</head>
<body>
    <div class="mainbody">
        <div class="header">
            <div class="logo">
                <img src="../../img/logo.png">
            </div>
            <div class="texts">
                <div class="title"><?php echo COMPANY_NAME; ?></div>
                <div class="subtitle">Kampri Bldg, 2254 Don Chino Roces Avenue, Makati City, Metro Manila</div>
            </div>
        </div>
        <div class="content">
            <div class="title">Inventory Report</div>
            <div class="liner text-right"><?php echo date('F d, Y h:iA'); ?></div>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Supplied By</th>
                        <th>Quantity (Box)</th>
                        <th>Excess Quantity (Box)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $todayStamp = date('Y-m-d');

                        $query = mysqli_query($connection, "SELECT `inventories`.*,
                                `products`.`name` AS `product_name`,
                                `suppliers`.`name` AS `supplier_name`
                            FROM `inventories`
                            INNER JOIN `products`
                                ON `inventories`.`product_id`=`products`.`id`
                            LEFT JOIN `suppliers`
                                ON `inventories`.`supplier_id`=`suppliers`.`id`
                            WHERE `inventories`.`created_at` LIKE '$todayStamp%'");

                        if(mysqli_num_rows($query) > 0) {
                            while($row = mysqli_fetch_assoc($query)) {
                    ?>
                    <tr>
                        <td><?php echo $row['product_name']; ?></td>
                        <td><?php echo $row['supplier_name']; ?></td>
                        <td><?php echo $row['boxes_arrived']; ?></td>
                        <td><?php echo $row['excess_boxes']; ?></td>
                    </tr>
                    <?php
                            }
                        } else {
                    ?>
                    <tr>
                        <td class="text-center" colspan="4">No results found.</td>
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<?php
    $html = ob_get_clean();
    $dompdf = new Dompdf();

    $options = new Options();
    $options->setIsRemoteEnabled(true);

    $dompdf->setOptions($options);
    $dompdf->loadHtml($html);
    $dompdf->render();
    $dompdf->stream($today . '_inventory_report.pdf', [
        'Attachment' => false
    ]);

    exit();
?>
