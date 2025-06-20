<?php
session_start();

// Validar sesión
if (!isset($_SESSION['id_u'])) {
    header("Location: login.php");
    exit;
}

require_once('../Config/conexion.php');

$id_usuario = $_SESSION['id_u'];

$sql = "SELECT * FROM productoaca WHERE id_usuario = ? AND borrado = 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$productos = [];

while ($row = $result->fetch_assoc()) {
    $productos[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos Académicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../public/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Winky+Sans:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<nav class="navbar navbar-expand-lg bg-primary navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand">Productos Académicos</a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item me-3">
          <span class="navbar-text text-white">
            Docente: <?php echo htmlspecialchars($_SESSION['nick']); ?>
          </span>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
            <i class="fa-solid fa-bars"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="../Administrador/Admin.html">Cerrar sesión</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <h3 class="mb-4 text-center" id="listArt">Lista de Productos Académicos</h3>

  <div class="mb-4 text-end">
    <button id="btnCrear" class="btn btn-success">Crear Artículo</button>
  </div>

  <div id="listaArticulos" class="list-group">
    <?php if (count($productos) > 0): ?>
      <?php foreach ($productos as $producto): ?>
        <div class="list-group-item d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-1"><?php echo htmlspecialchars($producto['titulo']); ?></h5>
            <p class="mb-1">Estado: <?php echo htmlspecialchars($producto['Estatus']); ?></p>
            <p class="mb-1">Fecha de Inicio: <?php echo $producto['fecha_inicio']; ?></p>
            <p class="mb-1">Fecha de Término: <?php echo $producto['fecha_termino']; ?></p>
            <p class="mb-1">Calificación: <?php echo isset($producto['calificacion']) ? $producto['calificacion'] : 'N/A'; ?>/10.00</p>
            <?php if (!empty($producto['urlConsulta'])): ?>
              <a href="../Documentos/ISC/<?php echo $producto['urlConsulta']; ?>" target="_blank">Ver Documento</a>
            <?php endif; ?>
          </div>
          <div>
            <button class="btn btn-sm btn-warning me-2">Editar</button>
            <button class="btn btn-sm btn-danger">Eliminar</button>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="alert alert-info">No tienes productos registrados aún.</div>
    <?php endif; ?>
  </div>

  <!-- Formulario para crear artículo -->
  <div id="formularioArticulo" class="card p-4 d-none">
    <h3 class="mb-4 text-center">Crear Nuevo Artículo</h3>
    <form action="conProAc.php" method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="titulo" class="form-label">Título del Artículo</label>
        <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Ingrese el título" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Estado del Artículo</label>
        <select name="estatus" class="form-select" required>
          <option value="">Seleccione el estado</option>
          <option value="1">Realizado</option>
          <option value="2">En proceso</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="fechaInicio" class="form-label">Fecha de Inicio</label>
        <input type="date" class="form-control" name="fechaInicio" id="fechaInicio" required>
      </div>

      <div class="mb-3">
        <label for="fechaTermino" class="form-label">Fecha de Término</label>
        <input type="date" class="form-control" name="fechaTermino" id="fechaTermino" required>
      </div>

      <div class="mb-3">
        <label for="archivoPDF" class="form-label">Subir Documento PDF (Nombrar: nombre_siglascarrera)</label>
        <input class="form-control" type="file" name="archivoPDF" id="archivoPDF" accept="application/pdf" required>
      </div>

      <button type="submit" class="btn btn-primary">Guardar</button>
      <button type="button" id="btnCancelar" class="btn btn-secondary">Cancelar</button>
    </form>
  </div>

</div>

<script>
  document.getElementById('btnCrear').addEventListener('click', function () {
    document.getElementById('listaArticulos').classList.add('d-none');
    document.getElementById('formularioArticulo').classList.remove('d-none');
    document.getElementById('btnCrear').classList.add('d-none');
    document.getElementById('listArt').classList.add('d-none');
  });

  document.getElementById('btnCancelar').addEventListener('click', function () {
    document.getElementById('formularioArticulo').classList.add('d-none');
    document.getElementById('listaArticulos').classList.remove('d-none');
    document.getElementById('btnCrear').classList.remove('d-none');
    document.getElementById('listArt').classList.remove('d-none');
  });
</script>

</body>
</html>
