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
    <script type="text/php">
        if(isset($pdf)) {
            $font = $fontMetrics->get_font('helvetica', 'normal');
            $size = 10;
            $y = $pdf->get_height() - 24;
            $x = $pdf->get_width() - 15 - $fontMetrics->get_text_width('1/1', $font, $size);
            $pdf->page_text($x, $y, '{PAGE_NUM} / {PAGE_COUNT}', $font, $size);
        }
    </script>
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
            <div class="title">Removed Products Report</div>
            <div class="liner text-right"><?php echo date('F d, Y h:iA'); ?></div>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="15%">Product Code</th>
                        <th>Name</th>
                        <th width="15%">Category</th>
                        <th width="15%">Type</th>
                        <th width="25%">Date & Time Removed</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $todayStamp = date('Y-m-d');

                        $query = mysqli_query($connection, "SELECT * FROM `removed_products`
                            WHERE `created_at` LIKE '$todayStamp%'");

                        if(mysqli_num_rows($query) > 0) {
                            while($row = mysqli_fetch_assoc($query)) {
                    ?>
                    <tr>
                        <td><?php echo $row['product_code']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['category']; ?></td>
                        <td><?php echo $row['type']; ?></td>
                        <td><?php echo date('F d, Y h:iA', strtotime($row['created_at'])); ?></td>
                    </tr>
                    <?php
                            }
                        } else {
                    ?>
                    <tr>
                        <td class="text-center" colspan="5">No results found.</td>
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
    $options->setIsPhpEnabled(true);

    $dompdf->setOptions($options);
    $dompdf->loadHtml($html);
    $dompdf->render();
    $dompdf->stream($today . '_removed_products_report.pdf', [
        'Attachment' => false
    ]);

    exit();
?>
