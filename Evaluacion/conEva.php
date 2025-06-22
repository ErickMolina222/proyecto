<?php
ob_start();
error_reporting(0);
ini_set('display_errors', 0);
session_start();

header("Content-Type: application/json");

if (!isset($_SESSION['id_u']) || !isset($_SESSION['id_p']) || !isset($_SESSION['nick'])) {
    http_response_code(401);
    echo json_encode(["error" => "No autorizado"]);
    ob_end_flush();
    exit;
}

require_once('../Config/conexion.php');

$id_usuario = $_SESSION['id_u'];
$id_perfil = $_SESSION['id_p'];
$nick = $_SESSION['nick'];

$productos = [];

$mapaDirectorios = [
    "Ingenieria en Sistemas" => "ISC",
    "Ingenieria Industrial" => "IIND",
    "Ingenieria Informatica" => "INF",
    "Ingenieria Electronica" => "ELEC",
    "Ingenieria Electromecanica" => "ELECM",
    "Ingenieria en Administracion" => "ADMI"
];

if ($id_perfil == 3) {
    // Jef@ de carrera
    $sqlCarrera = "SELECT id_carrera FROM persona WHERE id_u = ?";
    $stmtCarrera = $conn->prepare($sqlCarrera);
    $stmtCarrera->bind_param("i", $id_usuario);
    $stmtCarrera->execute();
    $resultCarrera = $stmtCarrera->get_result();

    if ($rowCarrera = $resultCarrera->fetch_assoc()) {
        $id_carrera = $rowCarrera['id_carrera'];

        // Agregamos p.nombre AS docente
            $sql = "
                SELECT pa.id_pa, pa.titulo, pa.Estatus, pa.fecha_inicio, pa.fecha_termino, 
                    pa.calificacion, pa.urlConsulta, c.nombreCa, p.nombre AS docente
                FROM productoaca pa
                INNER JOIN usuario u ON pa.id_usuario = u.id_u
                INNER JOIN persona p ON u.id_u = p.id_u
                INNER JOIN carrera c ON p.id_carrera = c.id_ca
                WHERE p.id_carrera = ? AND pa.borrado = 0
            ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_carrera);
    } else {
        echo json_encode([
            "nick" => $nick,
            "productos" => []
        ]);
        ob_end_flush();
        exit;
    }
} else {
    // Usuario normal
    $sql = "
        SELECT pa.id_pa, pa.titulo, pa.Estatus, pa.fecha_inicio, pa.fecha_termino, 
            pa.calificacion, pa.urlConsulta, c.nombreCa, p.nombre AS docente
        FROM productoaca pa
        INNER JOIN usuario u ON pa.id_usuario = u.id_u
        INNER JOIN persona p ON u.id_u = p.id_u
        INNER JOIN carrera c ON p.id_carrera = c.id_ca
        WHERE pa.id_usuario = ? AND pa.borrado = 0
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $row['carpeta'] = $mapaDirectorios[$row['nombreCa']];
    unset($row['nombreCa']);
    $productos[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode([
    "nick" => $nick,
    "productos" => $productos
]);

ob_end_flush();
?>
