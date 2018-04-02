<?php
    require_once(__DIR__ . '/../vendor/autoload.php');
    
    use Endroid\QrCode\QrCode;

    $text = isset($_GET['text']) && $_GET['text'] != '' ? $_GET['text'] : null;

    if($text != null) {
        $qrCode = new QrCode($text);

        header('Content-Type: ' . $qrCode->getContentType());

        echo $qrCode->writeString();
    }

    exit();
?>
