<?php
require('../fpdf/fpdf.php');
require_once('../Config/conexion.php');
require_once('../Main/graficarDatos.php');

$sql = "CALL ObtenerConteoPerfiles()";
$result = $conn->query($sql);


$datos = ['representante' => 0, 'administrador' => 0];
while ($row = $result->fetch_assoc()) {
    $perfil = strtolower($row['perfil']);
    if (isset($datos[$perfil])) {
        $datos[$perfil] = $row['cantidad'];
    }
}
$total = $datos['representante'] + $datos['administrador'];

$imagePath = 'grafica_usuarios.png';
crearGrafica($datos['representante'], $datos['administrador'], $imagePath);


$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Logos
$pdf->Image('../public/imagenes/estado.png', 10, 10, 30);
$pdf->Image('../public/imagenes/TESCHA.png', 170, 10, 30);

// Encabezado
$pdf->SetXY(60, 15);
$pdf->Cell(90, 10, 'Equipo No. 1', 0, 1, 'C');

// Título
$pdf->Ln(20);
$pdf->Cell(0, 10, "Usuarios Totales: $total", 0, 1, 'L');
$pdf->Ln(5);

$pdf->Image($imagePath, 40, null, 130);


$pdf->Output("reporte_usuarios.pdf", 'D');

$conn->close();
if (file_exists($imagePath)) {
    unlink($imagePath); 
}
?>
