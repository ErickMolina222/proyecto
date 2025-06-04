<?php
session_start();
require_once('../Config/conf.inc');
require_once('../Config/conexion.php');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_p'])) {
    header('Location: ../Main/index.php');
    exit();
}

$id_perfil = $_SESSION['id_p']; // Obtener el perfil del usuario desde la sesión

// Obtener los módulos visibles para el perfil del usuario
$sql_modulos_visibles = "SELECT m.Nombre, m.URL
                         FROM modulo m
                         JOIN mod_perfil mp ON m.id_mod = mp.id_mod
                         WHERE mp.id_p = ? AND m.Borrado = '0'";
$stmt_modulos_visibles = $conn->prepare($sql_modulos_visibles);
$stmt_modulos_visibles->bind_param("i", $id_perfil);
$stmt_modulos_visibles->execute();
$result_modulos_visibles = $stmt_modulos_visibles->get_result();

// Función para formatear el nombre del módulo como acción para bitácora
function formatearAccion($nombre) {
    return str_replace(' ', '_', trim($nombre));
}

// Preparamos los datos a enviar
$modulos = [];
while ($modulo = $result_modulos_visibles->fetch_assoc()) {
    $modulos[] = [
        'Nombre' => $modulo['Nombre'],
        'URL' => $modulo['URL'],
        'accion' => formatearAccion($modulo['Nombre'])
    ];
}

// Datos del usuario
$usuario = $_SESSION['usuario'];

// Cerramos la conexión
$conn->close();

// Enviamos los datos como respuesta JSON
echo json_encode([
    'usuario' => $usuario,
    'modulos' => $modulos
]);
?>
