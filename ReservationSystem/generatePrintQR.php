<?php
$isDirectAccess = !isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER']);

if ($isDirectAccess) {
    // Vista HTML para impresión automática
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
            }
            @media print {
                #printBtn { display: none; }
            }
        </style>
    </head>
    <body>
        <div>
            <img src="?print=false" style="width: 300px; height: 300px;">
            <button id="printBtn" onclick="window.print()">Imprimir</button>
        </div>
        <script>
            window.onload = function() {
                window.print();
            }
        </script>
    </body>
    </html>
    <?php
} else {
    // Generación normal del QR
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
}