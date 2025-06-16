<?php
session_start();
include '../Config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $contraseña = trim($_POST['contraseña']);

    if (empty($usuario) || empty($contraseña)) {
        header('Location: ../Main/index.html?error=2');
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM usuario WHERE LOWER(Nick) = LOWER(?) AND Borrado = '0'");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $contraseña === $user['Pwd']) {
        // Guardar datos de sesión
        $_SESSION['usuario'] = $user['Nick'];
        $_SESSION['id_p'] = $user['id_p'];
        $_SESSION['id_u'] = $user['id_u'];
        $_SESSION['nick'] = $user['Nick'];

        // REGISTRAR INICIO DE SESIÓN EN BITÁCORA (aquí mismo)
        $accion = 'INICIO SESION';
        $fecha = date('Y-m-d');
        $nick = $_SESSION['nick'];
        $id_u = $_SESSION['id_u'];

        $stmt_log = $conn->prepare("INSERT INTO bitacora (nick, fecha, hora, accion, id_u) VALUES (?, ?, CURTIME(), ?, ?)");
        $stmt_log->bind_param("sssi", $nick, $fecha, $accion, $id_u);
        $stmt_log->execute();
        $stmt_log->close();

        // Redirigir según perfil
        switch ($user['id_p']) {
            case 1:
                header('Location: ../Administrador/Admin.html');
                break;
            case 2:
                header('Location: ../Administrador/Admin.html');
                break;
            case 3:
                header('Location: ../Administrador/Admin.html');
                break;
            case 4:
                header('Location: ../Administrador/Admin.html');
                break;
            case 5:
                header('Location: ../Administrador/Admin.html');
                break;
            default:
                header('Location: ../Main/index.html');
        }
        exit();
    } else {
        header('Location: ../Main/index.html?error=1'); // Credenciales incorrectas
        exit();
    }
}
?>
