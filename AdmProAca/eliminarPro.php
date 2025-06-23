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

    // Primero obtenemos el nombre del archivo y la carrera del usuario
    $stmt = $conn->prepare("
        SELECT pa.urlConsulta, c.nombreCa 
        FROM productoaca pa
        INNER JOIN usuario u ON pa.id_usuario = u.id_u
        INNER JOIN persona p ON u.id_u = p.id_u
        INNER JOIN carrera c ON p.id_carrera = c.id_ca
        WHERE pa.id_pa = ? AND pa.id_usuario = ?
    ");
    $stmt->bind_param("ii", $id_pa, $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $producto = $result->fetch_assoc();
        $archivo = $producto['urlConsulta'];
        $carrera = $producto['nombreCa'];

        // Mapeo de carreras a carpetas
        $mapaDirectorios = [
            "Ingenieria en Sistemas" => "ISC",
            "Ingenieria Industrial" => "IIND",
            "Ingenieria Informatica" => "INF",
            "Ingenieria Electronica" => "ELEC",
            "Ingenieria Electromecanica" => "ELECM",
            "Ingenieria en Administracion" => "ADMI"
        ];

        $carpeta = isset($mapaDirectorios[$carrera]) ? $mapaDirectorios[$carrera] : "";

        // Eliminamos el archivo físico si existe
        if (!empty($archivo) && !empty($carpeta) && file_exists("../Documentos/$carpeta/" . $archivo)) {
            unlink("../Documentos/$carpeta/" . $archivo);
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
