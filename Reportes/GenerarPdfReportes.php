<?php
require_once('../Config/conf.inc');
require_once('../Config/conexion.php');
require('../fpdf/fpdf.php');

$idCarrera = isset($_POST['carrera']) ? intval($_POST['carrera']) : 0;
$idProfesor = isset($_POST['profesor']) ? intval($_POST['profesor']) : 0;

$filtros = array();
if ($idCarrera) $filtros[] = "p.id_carrera = $idCarrera";
if ($idProfesor) $filtros[] = "u.id_u = $idProfesor";
$where = count($filtros) > 0 ? "WHERE " . implode(" AND ", $filtros) : "";

$sql = "SELECT IFNULL(p.nombre, '') as profesor, u.Nick as nick, IFNULL(c.nombreCa, '') as carrera,
pa.titulo, pa.Estatus as estatus, pa.fecha_inicio, pa.fecha_termino, IFNULL(pa.calificacion, 'No asignada') as calificacion
FROM productoaca pa 
INNER JOIN usuario u ON pa.id_usuario = u.id_u
LEFT JOIN persona p ON u.id_u = p.id_u 
LEFT JOIN carrera c ON p.id_carrera = c.id_ca 
$where ORDER BY carrera, profesor, pa.fecha_inicio";

$res = $conn->query($sql);
if (!$res) { echo json_encode(['status'=>'error','message'=>'Error: '.$conn->error]); exit; }

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,10,'Reporte Academico de Profesores',0,1,'C');
$pdf->Ln(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(40,8,'Profesor',1);
$pdf->Cell(40,8,'Carrera',1);
$pdf->Cell(40,8,'Titulo',1);
$pdf->Cell(20,8,'Estado',1);
$pdf->Cell(20,8,'Calif.',1);
$pdf->Ln();

$pdf->SetFont('Arial','',9);
while($row = $res->fetch_assoc()) {
    $pdf->Cell(40,8,utf8_decode($row['profesor'].' ('.$row['nick'].')'),1);
    $pdf->Cell(40,8,utf8_decode($row['carrera']),1);
    $pdf->Cell(40,8,utf8_decode(substr($row['titulo'],0,20)),1);
    $pdf->Cell(20,8,$row['estatus'],1);
    $pdf->Cell(20,8,$row['calificacion'],1);
    $pdf->Ln();
}

$carpeta = "../Documentos/GENERAL/";
if ($idCarrera) {
    $map = [ 1 => 'ISC', 2 => 'IIND', 3 => 'INF', 4 => 'ELEC', 5 => 'ELECM', 6 => 'ADMI' ];
    $carpeta = "../Documentos/" . ($map[$idCarrera] ?? 'GENERAL') . "/";
}
if (!file_exists($carpeta)) {
    mkdir($carpeta, 0777, true);
}
$nombrePDF = "Reporte_".date("Ymd_His").".pdf";
$pdf->Output($carpeta.$nombrePDF, 'F');

echo json_encode(['status'=>'success','ruta'=>$carpeta.$nombrePDF]);
exit;
