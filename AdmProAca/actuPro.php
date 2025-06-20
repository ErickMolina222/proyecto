<?php
session_start();

if (!isset($_SESSION['id_u'])) {
    header("Location: login.php");
    exit;
}

require_once('../Config/conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pa = intval($_POST['id_pa']);
    $titulo = trim($_POST['titulo']);
    $estatus = $_POST['estatus'];
    $fechaInicio = $_POST['fechaInicio'];
    $fechaTermino = $_POST['fechaTermino'];
    $id_usuario = $_SESSION['id_u'];

    $stmt = $conn->prepare("UPDATE productoaca SET Estatus = ?, titulo = ?, fecha_inicio = ?, fecha_termino = ? 
                            WHERE id_pa = ? AND id_usuario = ?");
    $stmt->bind_param("ssssii", $estatus, $titulo, $fechaInicio, $fechaTermino, $id_pa, $id_usuario);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error al actualizar: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
