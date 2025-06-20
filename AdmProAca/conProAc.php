<?php
session_start();

if (!isset($_SESSION['id_u'])) {
    header("Location: login.php");
    exit;
}

require_once('../Config/conexion.php');

$id_usuario = $_SESSION['id_u'];

// Directorio de almacenamiento
$directorioDestino = "../Documentos/ISC/";
if (!is_dir($directorioDestino)) {
    mkdir($directorioDestino, 0777, true);
}

// Validar datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = trim($_POST['titulo']);
    $estatus = ($_POST['estatus'] == "1") ? "Realizado" : "En proceso";
    $fechaInicio = $_POST['fechaInicio'];
    $fechaTermino = $_POST['fechaTermino'];

    // Validar archivo
    if (isset($_FILES['archivoPDF']) && $_FILES['archivoPDF']['error'] == 0) {
        $archivo = $_FILES['archivoPDF'];
        $nombreArchivo = basename($archivo['name']);
        $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

        if ($extension != 'pdf') {
            die("Error: Solo se permite subir archivos PDF.");
        }

        // Renombrar el archivo para evitar sobrescrituras (por fecha)
        $nuevoNombre = time() . '-' . $nombreArchivo;
        $rutaDestino = $directorioDestino . $nuevoNombre;

        if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            // Guardamos en la base de datos
            $stmt = $conn->prepare("INSERT INTO productoaca (Estatus, titulo, fecha_inicio, fecha_termino, urlConsulta, borrado, id_usuario) 
                                    VALUES (?, ?, ?, ?, ?, 0, ?)");
            $stmt->bind_param("sssssi", $estatus, $titulo, $fechaInicio, $fechaTermino, $nuevoNombre, $id_usuario);

            if ($stmt->execute()) {
                // Redirige de vuelta al index después de guardar
                header("Location: index.php");
                exit;
            } else {
                echo "Error al insertar en la base de datos: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error al subir el archivo.";
        }
    } else {
        echo "Debe seleccionar un archivo PDF.";
    }
} else {
    echo "Acceso inválido al formulario.";
}

$conn->close();
?>
