<?php
require_once('../Config/conf.inc');
require_once('../Config/conexion.php');
header('Content-Type: application/json');

if (isset($_POST['accion']) && $_POST['accion'] == 'cargarFiltros') {
    $carreras = [];
    $profesores = [];
    $sqlCarreras = "SELECT id_ca, nombreCa FROM carrera ORDER BY nombreCa";
    $resCarreras = $conn->query($sqlCarreras);
    while($row = $resCarreras->fetch_assoc()) { $carreras[] = $row; }
    $sqlProfesores = "SELECT u.id_u, u.Nick as nick, p.nombre, p.id_carrera FROM usuario u INNER JOIN persona p ON u.id_u = p.id_u WHERE u.Borrado = '0'";
    $resProfesores = $conn->query($sqlProfesores);
    while($row = $resProfesores->fetch_assoc()) { $profesores[] = $row; }
    echo json_encode(['status' => 'success', 'carreras' => $carreras, 'profesores' => $profesores]);
    exit;
}

$idCarrera = isset($_POST['carrera']) ? intval($_POST['carrera']) : 0;
$idProfesor = isset($_POST['profesor']) ? intval($_POST['profesor']) : 0;

$filtros = array();
if ($idCarrera) $filtros[] = "p.id_carrera = $idCarrera";
if ($idProfesor) $filtros[] = "u.id_u = $idProfesor";
$where = count($filtros) > 0 ? "WHERE " . implode(" AND ", $filtros) : "";

$sql = "SELECT IFNULL(p.nombre, '') as profesor, u.Nick as nick, IFNULL(c.nombreCa, '') as carrera,
pa.titulo, pa.Estatus as estatus, pa.fecha_inicio, pa.fecha_termino, pa.calificacion, pa.DocumentoProvatorio as documento
FROM productoaca pa 
INNER JOIN usuario u ON pa.id_usuario = u.id_u
LEFT JOIN persona p ON u.id_u = p.id_u 
LEFT JOIN carrera c ON p.id_carrera = c.id_ca 
$where ORDER BY carrera, profesor, pa.fecha_inicio";

$res = $conn->query($sql);
if (!$res) { echo json_encode(['status'=>'error','message'=>'Error: '.$conn->error]); exit; }

$datos = [];
while($row = $res->fetch_assoc()) {
    $row['calificacion'] = $row['calificacion'] !== null ? $row['calificacion'] : 'No asignada';
    $row['documento'] = $row['documento'] !== null ? $row['documento'] : 'No disponible';
    $datos[] = $row;
}
echo json_encode(['status'=>'success','datos'=>$datos]);
?>
