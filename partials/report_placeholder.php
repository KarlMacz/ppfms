<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo COMPANY_NAME; ?></title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>
        html,
        body {
            padding: 0;
            margin: 0;
            height: 100%;
        }

        body {
            background-color: #eee;
            border: 1px dashed #777;
            font-family: 'Helvetica';
            font-size: 15px;
        }

        .flexify {
            align-items: center;
            display: flex;
            justify-content: center;
            height: 100%;
            width: 100%;
        }

        .flexify > .flexify-content {
            background-color: white;
            box-shadow: 0 2px 2px rgba(34, 34, 34, 0.25);
            padding: 25px;
            min-width: 50%;
            max-width: 90%;
        }
    </style>
</head>
<body>
    <div class="flexify">
        <div class="flexify-content text-center">Generated report will be displayed here.</div>
    </div>
</body>
</html>
