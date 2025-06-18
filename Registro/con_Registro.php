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
    $carrera = intval($_POST['carrera']); // Nuevo parámetro

    $sql = "CALL registrar_usuario_persona(?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("sisssii", $nombre, $edad, $correo, $nick, $pwd, $perfil, $carrera);

    if ($stmt->execute()) {
        echo "<script>alert('Registro exitoso'); window.location.href='../Main/';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
