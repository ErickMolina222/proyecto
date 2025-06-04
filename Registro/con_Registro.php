<?php 
session_start();
include '../Config/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $edad = intval($_POST['edad']);
    $correo = $_POST['correo'];
    $nick = $_POST['nick'];
    $pwd = $_POST['pwd'];
    $perfil = intval($_POST['perfil']);

    $sql = "CALL registrar_usuario_persona(?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("sisssi", $nombre, $edad, $correo, $nick, $pwd, $perfil);

    if ($stmt->execute()) {
        echo "<script>alert('Registro exitoso'); window.location.href='index.html';</script>";
    } else {
        // Muestra el mensaje de error lanzado desde el procedimiento
        echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
