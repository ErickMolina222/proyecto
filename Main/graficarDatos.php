<?php
function crearGrafica($representantes, $administradores, $outputPath) {
    $total = $representantes + $administradores;
    $image = imagecreate(400, 300);

    $white = imagecolorallocate($image, 255, 255, 255);
    $blue = imagecolorallocate($image, 100, 149, 237); // Representantes
    $red = imagecolorallocate($image, 220, 20, 60);    // Administradores
    $black = imagecolorallocate($image, 0, 0, 0);

    $start = 0;
    if ($total > 0) {
        $repAngle = round(($representantes / $total) * 360);
        $admAngle = 360 - $repAngle;

        imagefilledarc($image, 100, 150, 180, 180, $start, $start + $repAngle, $blue, IMG_ARC_PIE);
        $start += $repAngle;
        imagefilledarc($image, 100, 150, 180, 180, $start, $start + $admAngle, $red, IMG_ARC_PIE);

        // Texto leyenda
        imagestring($image, 4, 220, 100, "Representante", $black);
        imagestring($image, 4, 220, 120, "{$representantes} usuarios", $black);
        imagestring($image, 4, 220, 140, round(($representantes * 100 / $total), 1) . "%", $blue);

        imagestring($image, 4, 220, 180, "Administrador", $black);
        imagestring($image, 4, 220, 200, "{$administradores} usuarios", $black);
        imagestring($image, 4, 220, 220, round(($administradores * 100 / $total), 1) . "%", $red);
    }

    imagepng($image, $outputPath);
    imagedestroy($image);
}
?>
