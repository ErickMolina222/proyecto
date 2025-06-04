<?php
session_start();
include '../Config/conexion.php';

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    header("Location: AdminU.php?error=ID inválido");
    exit();
}

$id = $_POST['id'];

// Obtener el estado actual del usuario
$stmt = $conn->prepare("SELECT borrado FROM usuario WHERE id_u = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

if (!$usuario) {
    header("Location: ../AdmUsuarios/con_AdminU.php?error=Usuario no encontrado");
    exit();
}

// Alternar el estado de 'borrado'
$nuevoEstado = ($usuario['borrado'] == 1) ? 0 : 1;

$stmt = $conn->prepare("UPDATE usuario SET borrado = ? WHERE id_u = ?");
$stmt->bind_param("ii", $nuevoEstado, $id);
if ($stmt->execute()) {
    // Registrar en bitácora si hay sesión activa
    if (isset($_SESSION['nick']) && isset($_SESSION['id_u'])) {
        $accion = ($nuevoEstado == 1) ? 'DESHABILITO USUARIO' : 'HABILITO USUARIO';
        $fecha = date('Y-m-d');
        $nick_sesion = $_SESSION['nick'];
        $id_u_sesion = $_SESSION['id_u'];

        $stmt_log = $conn->prepare("INSERT INTO bitacora (nick, fecha, hora, accion, id_u) VALUES (?, ?, CURTIME(), ?, ?)");
        $stmt_log->bind_param("sssi", $nick_sesion, $fecha, $accion, $id_u_sesion);
        $stmt_log->execute();
        $stmt_log->close();
    }
}
$stmt->close();

header("Location: ../AdmUsuarios/con_AdminU.php?success=Estado actualizado");
exit();
?>
