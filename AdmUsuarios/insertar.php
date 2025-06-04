<?php 
session_start();
include '../Config/conexion.php';

// Agregar usuario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_u = $_POST['id_u'];
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $nick = $_POST['nick'];
    $pwd = $_POST['pwd'];
    $perfil = $_POST['perfil'];

    $sql = "INSERT INTO usuario (id_u, Nombre, Edad, Nick, Pwd, id_p) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isissi", $id_u, $nombre, $edad, $nick, $pwd, $perfil);

    if ($stmt->execute()) {
        // Solo registrar en bitácora si hay sesión activa
        if (isset($_SESSION['nick']) && isset($_SESSION['id_u'])) {
            $accion = 'CREO USUARIO';
            $fecha = date('Y-m-d');
            $nick_sesion = $_SESSION['nick'];
            $id_u_sesion = $_SESSION['id_u'];

            $stmt_log = $conn->prepare("INSERT INTO bitacora (nick, fecha, hora, accion, id_u) VALUES (?, ?, CURTIME(), ?, ?)");
            $stmt_log->bind_param("sssi", $nick_sesion, $fecha, $accion, $id_u_sesion);
            $stmt_log->execute();
            $stmt_log->close();
        }

        // Redirigir a con_AdminU.php después de agregar el usuario y registrar en la bitácora
        header("Location: ../AdmUsuarios/Adminu.html?success=Usuario agregado correctamente");
        exit();
    }

    $stmt->close();
}

// Obtener usuarios
$sql = "SELECT u.id_u, u.Nombre, u.Edad, u.Nick, p.Nombre AS Perfil 
        FROM usuario u 
        LEFT JOIN perfil p ON u.id_p = p.id_p 
        WHERE u.Borrado = '0'";
$result = $conn->query($sql);
$usuarios = $result->fetch_all(MYSQLI_ASSOC);
?>
