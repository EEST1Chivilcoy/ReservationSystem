<?php
if (isset($_GET['print']) && $_GET['print'] === 'true') {
    header('Content-Type: text/html');
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Imprimir QR</title>
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                background: white;
            }
            img { max-width: 300px; }
        </style>
    </head>
    <body>
        <img src="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?print=false'; ?>" alt="QR Code">
        <script>
            window.onload = () => window.print();
            window.onafterprint = () => window.close();
        </script>
    </body>
    </html>
    <?php
    exit;
}

header('Content-Type: image/png');
$websiteUrl = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
$apiUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($websiteUrl);

$qrImage = file_get_contents($apiUrl);
if ($qrImage !== false) {
    echo $qrImage;
} else {
    $img = imagecreatetruecolor(300, 100);
    $white = imagecolorallocate($img, 255, 255, 255);
    $red = imagecolorallocate($img, 255, 0, 0);
    imagefill($img, 0, 0, $white);
    imagestring($img, 5, 10, 40, "Error generando QR", $red);
    imagepng($img);
    imagedestroy($img);
}