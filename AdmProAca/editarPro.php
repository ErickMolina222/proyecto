<?php
session_start();

if (!isset($_SESSION['id_u'])) {
    header("Location: login.php");
    exit;
}

require_once('../Config/conexion.php');

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_pa = intval($_GET['id']);
$id_usuario = $_SESSION['id_u'];

// Obtener los datos del producto
$stmt = $conn->prepare("SELECT * FROM productoaca WHERE id_pa = ? AND id_usuario = ?");
$stmt->bind_param("ii", $id_pa, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    header("Location: index.php");
    exit;
}

$producto = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>

<div class="container mt-5">
    <h3>Editar Producto Académico</h3>
    <form action="actuPro.php" method="POST">
        <input type="hidden" name="id_pa" value="<?php echo $producto['id_pa']; ?>">

        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" class="form-control" name="titulo" value="<?php echo htmlspecialchars($producto['titulo']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="estatus" class="form-select" required>
                <option value="Realizado" <?php if($producto['Estatus']=="Realizado") echo "selected"; ?>>Realizado</option>
                <option value="En proceso" <?php if($producto['Estatus']=="En proceso") echo "selected"; ?>>En proceso</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha de Inicio</label>
            <input type="date" class="form-control" name="fechaInicio" value="<?php echo $producto['fecha_inicio']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha de Término</label>
            <input type="date" class="form-control" name="fechaTermino" value="<?php echo $producto['fecha_termino']; ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

</body>
</html>
