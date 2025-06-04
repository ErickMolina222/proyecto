<?php
session_start();
include '../Config/conexion.php';

// Verificar si la sesión está activa
if (isset($_SESSION['nick']) && isset($_SESSION['id_u'])) {
    $accion = 'CERRO SESION';
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');
    $nick_sesion = $_SESSION['nick'];
    $id_u_sesion = $_SESSION['id_u'];

    $stmt_log = $conn->prepare("INSERT INTO bitacora (nick, fecha, hora, accion, id_u) VALUES (?, ?, ?, ?, ?)");
    $stmt_log->bind_param("ssssi", $nick_sesion, $fecha, $hora, $accion, $id_u_sesion);
    $stmt_log->execute();
    $stmt_log->close();
}

// Responder con un mensaje de éxito
echo json_encode(["status" => "success"]);
?>
