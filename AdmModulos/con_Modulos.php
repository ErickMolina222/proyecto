<?php
session_start();
require_once('../Config/conexion.php');

// Obtener perfiles activos
$perfiles = $conn->query("SELECT * FROM perfil WHERE Borrado = '0'");

// Obtener módulos activos
$modulos = $conn->query("SELECT * FROM modulo WHERE Borrado = '0'");
$modulos_array = [];
while ($mod = $modulos->fetch_assoc()) {
    $modulos_array[] = $mod;
}

// REGISTRAR INGRESO A ADMINMODULOS EN BITÁCORA (una sola vez por sesión)
if (!isset($_SESSION['ingreso_ADMINMODULOS'])) {
    $accion = 'INGRESO A ADMINMODULOS';
    $fecha = date('Y-m-d');
    $nick = $_SESSION['nick'];
    $id_u = $_SESSION['id_u'];

    $stmt_log = $conn->prepare("INSERT INTO bitacora (nick, fecha, hora, accion, id_u) VALUES (?, ?, CURTIME(), ?, ?)");
    $stmt_log->bind_param("sssi", $nick, $fecha, $accion, $id_u);
    $stmt_log->execute();
    $stmt_log->close();

    // Marcar como registrado para esta sesión
    $_SESSION['ingreso_ADMINMODULOS'] = true;
}

// Procesar actualizaciones
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    $id_p = (int)$_POST['perfil_id'];
    $modulos_seleccionados = isset($_POST['modulos_' . $id_p]) ? $_POST['modulos_' . $id_p] : [];

    // Eliminar anteriores
    $stmt_del = $conn->prepare("DELETE FROM mod_perfil WHERE id_p = ?");
    $stmt_del->bind_param("i", $id_p);
    $stmt_del->execute();

    // Insertar seleccionados
    foreach ($modulos_seleccionados as $id_mod) {
        $stmt_ins = $conn->prepare("INSERT INTO mod_perfil (id_mod, id_p) VALUES (?, ?)");
        $stmt_ins->bind_param("ii", $id_mod, $id_p);
        $stmt_ins->execute();
    }

    header("Location: con_Modulos.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Módulos por Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Winky Sans', sans-serif;
        }
        table th, table td {
            vertical-align: middle;
            text-align: center;
        }
    </style>
</head>
<body class="container py-4">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Perfil</th>
                <?php foreach ($modulos_array as $mod): ?>
                    <th><?= htmlspecialchars($mod['Nombre']) ?></th>
                <?php endforeach; ?>
                <th>Actualizar</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($perfil = $perfiles->fetch_assoc()): ?>
                <?php
                    $id_p = $perfil['id_p'];
                    $mod_activos = [];
                    $res_mods = $conn->prepare("SELECT id_mod FROM mod_perfil WHERE id_p = ?");
                    $res_mods->bind_param("i", $id_p);
                    $res_mods->execute();
                    $resultado = $res_mods->get_result();
                    while ($m = $resultado->fetch_assoc()) {
                        $mod_activos[] = $m['id_mod'];
                    }
                ?>
                <tr>
                    <form method="POST" action="con_Modulos.php">
                        <td><strong><?= htmlspecialchars($perfil['Nombre']) ?></strong></td>
                        <?php foreach ($modulos_array as $mod): ?>
                            <td>
                                <input type="checkbox" name="modulos_<?= $id_p ?>[]" value="<?= $mod['id_mod'] ?>"
                                    <?= in_array($mod['id_mod'], $mod_activos) ? 'checked' : '' ?>>
                            </td>
                        <?php endforeach; ?>
                        <td>
                            <input type="hidden" name="perfil_id" value="<?= $id_p ?>">
                            <button type="submit" name="actualizar" class="btn btn-sm btn-primary">Actualizar</button>
                        </td>
                    </form>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php $conn->close(); ?>
