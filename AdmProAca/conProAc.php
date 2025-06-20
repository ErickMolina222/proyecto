<?php
session_start();

if (!isset($_SESSION['id_u'])) {
    header("Location: login.php");
    exit;
}

require_once('../Config/conexion.php');
$id_usuario = $_SESSION['id_u'];

// Mapeo carrera → carpeta
$mapaDirectorios = [
    "Ingenieria en Sistemas" => "ISC",
    "Ingenieria Industrial" => "IIND",
    "Ingenieria Informatica" => "INF",
    "Ingenieria Electronica" => "ELEC",
    "Ingenieria Electromecanica" => "ELECM",
    "Ingenieria en Administracion" => "ADMI"
];

$carrera = $_SESSION['carrera'];

if (!isset($mapaDirectorios[$carrera])) {
    die("Error: Carrera no reconocida.");
}

$carpetaCarrera = $mapaDirectorios[$carrera];
$directorioDestino = "../Documentos/$carpetaCarrera/";

if (!is_dir($directorioDestino)) {
    mkdir($directorioDestino, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_pa = isset($_POST['id_pa']) ? intval($_POST['id_pa']) : 0;
    $titulo = trim($_POST['titulo']);
    $estatus = ($_POST['estatus'] == "1") ? "Realizado" : "En proceso";
    $fechaInicio = $_POST['fechaInicio'];
    $fechaTermino = $_POST['fechaTermino'];

    // Si estamos actualizando
    if ($id_pa > 0) {

        // Si sube un nuevo archivo PDF
        if (isset($_FILES['archivoPDF']) && $_FILES['archivoPDF']['error'] == 0) {
            $archivo = $_FILES['archivoPDF'];
            $nombreArchivo = basename($archivo['name']);
            $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

            if ($extension != 'pdf') {
                die("Error: Solo se permite subir archivos PDF.");
            }

            $nuevoNombre = time() . '-' . $nombreArchivo;
            $rutaDestino = $directorioDestino . $nuevoNombre;

            if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
                // Borramos el PDF anterior
                $stmtOld = $conn->prepare("SELECT urlConsulta FROM productoaca WHERE id_pa = ? AND id_usuario = ?");
                $stmtOld->bind_param("ii", $id_pa, $id_usuario);
                $stmtOld->execute();
                $resultOld = $stmtOld->get_result();
                if ($resultOld->num_rows == 1) {
                    $oldFile = $resultOld->fetch_assoc()['urlConsulta'];
                    if (!empty($oldFile) && file_exists($directorioDestino . $oldFile)) {
                        unlink($directorioDestino . $oldFile);
                    }
                }
                $stmtOld->close();

                $stmt = $conn->prepare("UPDATE productoaca SET Estatus = ?, titulo = ?, fecha_inicio = ?, fecha_termino = ?, urlConsulta = ? WHERE id_pa = ? AND id_usuario = ?");
                $stmt->bind_param("ssssssi", $estatus, $titulo, $fechaInicio, $fechaTermino, $nuevoNombre, $id_pa, $id_usuario);
            } else {
                die("Error al subir el nuevo archivo PDF.");
            }
        } else {
            // Si no sube nuevo archivo, solo actualizamos los demás campos
            $stmt = $conn->prepare("UPDATE productoaca SET Estatus = ?, titulo = ?, fecha_inicio = ?, fecha_termino = ? WHERE id_pa = ? AND id_usuario = ?");
            $stmt->bind_param("ssssii", $estatus, $titulo, $fechaInicio, $fechaTermino, $id_pa, $id_usuario);
        }

        $stmt->execute();
        $stmt->close();
        header("Location: index.php");
        exit;
    }

    // Si estamos creando uno nuevo
    if (isset($_FILES['archivoPDF']) && $_FILES['archivoPDF']['error'] == 0) {
        $archivo = $_FILES['archivoPDF'];
        $nombreArchivo = basename($archivo['name']);
        $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

        if ($extension != 'pdf') {
            die("Error: Solo se permite subir archivos PDF.");
        }

        $nuevoNombre = time() . '-' . $nombreArchivo;
        $rutaDestino = $directorioDestino . $nuevoNombre;

        if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            $stmt = $conn->prepare("INSERT INTO productoaca (Estatus, titulo, fecha_inicio, fecha_termino, urlConsulta, borrado, id_usuario) VALUES (?, ?, ?, ?, ?, 0, ?)");
            $stmt->bind_param("sssssi", $estatus, $titulo, $fechaInicio, $fechaTermino, $nuevoNombre, $id_usuario);
            $stmt->execute();
            $stmt->close();
            header("Location: index.php");
            exit;
        } else {
            die("Error al subir el archivo.");
        }
    } else {
        die("Debe seleccionar un archivo PDF para crear el nuevo artículo.");
    }
}

$conn->close();
?>
