<?php
session_start();
if (!isset($_SESSION['usuario']) || !isset($_SESSION['tipo_usuario'])) {
    header('Location: main.php');
    exit();
}

$tipo_usuario = $_SESSION['tipo_usuario'];
$titulo = "";
$mensaje = "";

switch ($tipo_usuario) {
    case 'administrador':
        $titulo = "Administrador";
        $mensaje = "Bienvenido Administrador";
        break;
    case 'estudiante':
        $titulo = "Estudiante";
        $mensaje = "Bienvenido Estudiante";
        break;
    case 'profesor':
        $titulo = "Profesor";
        $mensaje = "Bienvenido Profesor";
        break;
    default:
        header('Location: main.php');
        exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $titulo; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Winky+Sans:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Winky Sans', sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 50px;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }
        h2 {
            color: #333;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color:rgb(255, 0, 0);
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        a:hover {
            background-color:rgb(179, 0, 0);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><?php echo $mensaje; ?></h2>
        <a href="index.php">Cerrar sesión</a>
    </div>
</body>
</html>