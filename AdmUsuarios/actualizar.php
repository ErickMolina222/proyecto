<?php 
session_start();
include '../Config/conexion.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: administrar_usuarios.php?error=ID inválido");
    exit();
}

$id = $_GET['id'];
$mensaje = "";

$stmt = $conn->prepare("SELECT * FROM usuario WHERE id_u = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

if (!$usuario) {
    header("Location: administrar_usuarios.php?error=Usuario no encontrado");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $edad = trim($_POST['edad']);
    $nick = trim($_POST['nick']);
    $pwd = trim($_POST['pwd']);
    $perfil = trim($_POST['perfil']);

    if (!empty($pwd)) {
        $sql = "UPDATE usuario SET Nombre = ?, Edad = ?, Nick = ?, Pwd = ?, id_p = ? WHERE id_u = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisssi", $nombre, $edad, $nick, $pwd, $perfil, $id);
    } else {
        $sql = "UPDATE usuario SET Nombre = ?, Edad = ?, Nick = ?, id_p = ? WHERE id_u = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisii", $nombre, $edad, $nick, $perfil, $id);
    }

    if ($stmt->execute()) {
        $mensaje = "Usuario actualizado correctamente.";
        $stmt->close();

        // Registrar en bitácora
        if (isset($_SESSION['nick']) && isset($_SESSION['id_u'])) {
            $accion = 'ACTUALIZO USUARIO';
            $fecha = date('Y-m-d');
            $nick_sesion = $_SESSION['nick'];
            $id_u_sesion = $_SESSION['id_u'];

            $stmt_log = $conn->prepare("INSERT INTO bitacora (nick, fecha, hora, accion, id_u) VALUES (?, ?, CURTIME(), ?, ?)");
            $stmt_log->bind_param("sssi", $nick_sesion, $fecha, $accion, $id_u_sesion);
            $stmt_log->execute();
            $stmt_log->close();
        }

        // Recargar usuario actualizado
        $stmt = $conn->prepare("SELECT * FROM usuario WHERE id_u = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();
        $stmt->close();
    } else {
        $mensaje = "Error al actualizar usuario: " . $conn->error;
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<style>
    a {
        display: inline-block;
        margin-top: 5px;
        padding: 10px 20px;
        background-color: rgb(255, 0, 0);
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }
    a:hover {
        background-color: rgb(179, 0, 0);
    }
</style>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow-md">
        <h2 class="text-2xl font-bold text-green-600 mb-4 text-center">Editar Usuario</h2>

        <?php if (!empty($mensaje)): ?>
            <p class="text-center font-semibold p-2 <?php echo (strpos($mensaje, 'Error') !== false) ? 'text-red-600' : 'text-green-600'; ?>">
                <?php echo $mensaje; ?>
            </p>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="id_u" value="<?php echo $usuario['id_u']; ?>">

            <label>Nombre</label>
            <input type="text" name="nombre" value="<?php echo $usuario['Nombre']; ?>" class="w-full p-2 border rounded mb-4" required>

            <label>Edad</label>
            <input type="number" name="edad" value="<?php echo $usuario['Edad']; ?>" class="w-full p-2 border rounded mb-4" required>

            <label>Nick</label>
            <input type="text" name="nick" value="<?php echo $usuario['Nick']; ?>" class="w-full p-2 border rounded mb-4" required>

            <label>Contraseña (Déjalo vacío para no cambiarla)</label>
            <input type="password" name="pwd" class="w-full p-2 border rounded mb-4">

            <label>Perfil</label>
            <select name="perfil" class="w-full p-2 border rounded mb-4" required>
                <option value="1" <?php echo ($usuario['id_p'] == 1) ? 'selected' : ''; ?>>Representante</option>
                <option value="2" <?php echo ($usuario['id_p'] == 2) ? 'selected' : ''; ?>>Administrador</option>
            </select>

            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Actualizar Usuario</button>
        </form>

        <div class="text-center mt-4">
            <a href="../AdmUsuarios/con_AdminU.php">Volver</a>
        </div>
    </div>
</body>
</html>