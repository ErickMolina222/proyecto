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
    $id_usuario = $_SESSION['id_u'];

    if ($calificacion < 0 || $calificacion > 10) {
        http_response_code(400);
        echo "Calificación inválida.";
        exit;
    }

    $stmt = $conn->prepare("UPDATE productoaca SET calificacion = ?, Estatus = 'Calificado' WHERE id_pa = ? AND id_usuario = ?");
    $stmt->bind_param("dii", $calificacion, $id_pa, $id_usuario);
    $stmt->execute();
    $stmt->close();

    echo "Calificado correctamente.";
    exit;
}

http_response_code(405);
echo "Método no permitido.";
?>