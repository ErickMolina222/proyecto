<?php
session_start();

if (!isset($_SESSION['id_u'])) {
    header("Location: login.php");
    exit;
}

require_once('../Config/conexion.php');

if (isset($_GET['id'])) {
    $id_pa = intval($_GET['id']);
    $id_usuario = $_SESSION['id_u'];

    // Primero obtenemos el nombre del archivo para eliminarlo
    $stmt = $conn->prepare("SELECT urlConsulta FROM productoaca WHERE id_pa = ? AND id_usuario = ?");
    $stmt->bind_param("ii", $id_pa, $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $producto = $result->fetch_assoc();
        $archivo = $producto['urlConsulta'];

        // Eliminamos el archivo físico
        if (!empty($archivo) && file_exists("../Documentos/ISC/" . $archivo)) {
            unlink("../Documentos/ISC/" . $archivo);
        }

        // Ahora eliminamos el registro de la base de datos
        $delete = $conn->prepare("DELETE FROM productoaca WHERE id_pa = ? AND id_usuario = ?");
        $delete->bind_param("ii", $id_pa, $id_usuario);
        $delete->execute();
        $delete->close();
    }

    $stmt->close();
}

$conn->close();
header("Location: index.php");
exit;
?>
