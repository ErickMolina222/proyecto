<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $contraseña = trim($_POST['contraseña']);

    if (empty($usuario) || empty($contraseña)) {
        header('Location: index.php?error=2');
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM usuario WHERE LOWER(Nick) = LOWER(?) AND Borrado = '0'");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && $contraseña === $user['Pwd']) {
        $_SESSION['usuario'] = $user['Nick'];
        $_SESSION['id_p'] = $user['id_p'];

        switch ($user['id_p']) {
            case 2:
                header('Location: Admin.php');
                break;
            case 1:
                header('Location: estudiante.php');
                break;
            case 3:
                header('Location: profesor.php');
                break;
            default:
                header('Location: index.php');
        }
        exit();
    } else {
        header('Location: index.php?error=1'); // Error 1: Credenciales incorrectas
        exit();
    }
}
?>
