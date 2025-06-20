<?php
session_start();

if (!isset($_SESSION['id_u']) || !isset($_SESSION['id_p'])) {
    http_response_code(401);
    echo json_encode(["error" => "No autorizado"]);
    exit;
}

header("Content-Type: application/json");
require_once('../Config/conexion.php');

$id_usuario = $_SESSION['id_u'];
$id_perfil = $_SESSION['id_p'];

if ($id_perfil == 3) {
    // Si es jefa de carrera, primero obtenemos su carrera
    $sqlCarrera = "SELECT id_carrera FROM persona WHERE id_u = ?";
    $stmtCarrera = $conn->prepare($sqlCarrera);
    $stmtCarrera->bind_param("i", $id_usuario);
    $stmtCarrera->execute();
    $resultCarrera = $stmtCarrera->get_result();

    if ($rowCarrera = $resultCarrera->fetch_assoc()) {
        $id_carrera = $rowCarrera['id_carrera'];

        // Ahora buscamos todos los productos de usuarios de esa carrera
        $sql = "
            SELECT pa.id_pa, pa.titulo, pa.Estatus, pa.fecha_inicio, pa.fecha_termino, 
                   pa.calificacion, pa.urlConsulta
            FROM productoaca pa
            INNER JOIN usuario u ON pa.id_usuario = u.id_u
            INNER JOIN persona p ON u.id_u = p.id_u
            WHERE p.id_carrera = ? AND pa.borrado = 0
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_carrera);
    } else {
        // No tiene carrera asignada
        echo json_encode([]);
        exit;
    }
} else {
    // Para los demás usuarios solo sus productos
    $sql = "SELECT id_pa, titulo, Estatus, fecha_inicio, fecha_termino, calificacion, urlConsulta 
            FROM productoaca 
            WHERE id_usuario = ? AND borrado = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
}

$stmt->execute();
$result = $stmt->get_result();

$productos = [];
while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($productos);
?>
