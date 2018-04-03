<?php
    require_once('../../vendor/autoload.php');
    require_once('../database.php');
    require_once('../functions.php');

    use Dompdf\Dompdf;
    use Dompdf\Options;

    $id = input_escape_string($connection, $_GET['id']);

    $query = mysqli_query($connection, "SELECT * FROM `products`
        WHERE `id`='$id'");

    if(mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $productCode = $row['product_code'];

        ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bhagi's International Trading Corporation</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <style>
        @page {
            margin: 10px;
        }

        hr {
            border: none;
            border-top: 2px solid #ccc;
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
            font-size: 0.75em;
        }

        .header .logo {
            height: 50px;
        }

        .content {
            padding: 10px;
        }

        .content > .content-header {
            font-size: 1.5em;
            text-align: center;
            margin-bottom: 10px;
        }

        .footer {
            border-top: 2px solid #ccc;
            font-size: 0.75em;
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
    <img src="<?php echo MY_URL; ?>/backend/qr.php?text=<?php echo urlencode(MY_URL . '/products.php?search_for=' . $productCode); ?>" style="height: 100%; width: 100%;">
</body>
</html>
<?php
        $html = ob_get_clean();
        $dompdf = new Dompdf();

        $options = new Options();
        $options->setIsRemoteEnabled(true);

        $dompdf->setOptions($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper([
            0, 0, 100, 100
        ]);
        $dompdf->render();
        $dompdf->stream('product_' . $productCode . '.pdf', [
            'Attachment' => false
        ]);

        exit();
    } else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bhagi's International Trading Corporation</title>
    <link rel="stylesheet" href="../../css/fontawesome-all.min.css">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <script src="../../js/jquery-3.3.1.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
</head>
<body>
    <div class="alert alert-danger"><span class="fas fa-times fa-fw"></span> No results found.</div>
</body>
</html>
<?php
    }
?>
