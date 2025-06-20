<?php
session_start();

if (!isset($_SESSION['id_u'])) {
    http_response_code(401);
    echo "No autorizado";
    exit;
}

require_once('../Config/conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pa = intval($_POST['id_pa']);
    $calificacion = floatval($_POST['calificacion']);

    if ($calificacion < 0 || $calificacion > 10) {
        http_response_code(400);
        echo "Calificación inválida.";
        exit;
    }

    // Actualizar sin validar el usuario que lo creó
    $stmt = $conn->prepare("UPDATE productoaca SET calificacion = ?, Estatus = 'Calificado' WHERE id_pa = ?");
    $stmt->bind_param("di", $calificacion, $id_pa);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Calificado correctamente.";
    } else {
        http_response_code(404);
        echo "No se encontró el artículo o no se actualizó.";
    }

    $stmt->close();
    exit;
}

http_response_code(405);
echo "Método no permitido.";
?>
