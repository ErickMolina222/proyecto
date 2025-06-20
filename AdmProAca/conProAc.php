<?php
// Directorio donde se guardarán los archivos
$directorioDestino = "../Documentos/ISC/";

// Verificamos si el directorio existe, si no lo creamos
if (!is_dir($directorioDestino)) {
    mkdir($directorioDestino, 0777, true);
}

// Verificar si se ha enviado un archivo
if (isset($_FILES["archivoPDF"]) && $_FILES["archivoPDF"]["error"] == 0) {
    $nombreArchivo = basename($_FILES["archivoPDF"]["name"]);
    $rutaDestino = $directorioDestino . $nombreArchivo;

    // Validar que sea un PDF
    $tipoArchivo = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));
    if ($tipoArchivo != "pdf") {
        echo "Solo se permiten archivos PDF.";
        exit;
    }

    // Mover el archivo subido
    if (move_uploaded_file($_FILES["archivoPDF"]["tmp_name"], $rutaDestino)) {
        echo "El archivo se ha subido correctamente.";
        // Aquí puedes guardar el resto de los datos en base de datos si lo deseas
        // $_POST["titulo"], $_POST["estatus"], $_POST["fechaInicio"], $_POST["fechaTermino"]
    } else {
        echo "Hubo un error al subir el archivo.";
    }
} else {
    echo "No se ha enviado ningún archivo o ha ocurrido un error.";
}
?>
