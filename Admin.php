<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRINCIPAL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./public/fontawesome-free-6.7.2-web/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Winky+Sans:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Winky Sans', sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        .sidebar {
            height: 100vh;
            width: 250px;
            background-color:rgba(177, 177, 177, 0.18);
            padding: 20px;
        }
        .sidebar .nav-link {
            color: #000;
        }
        .container {
            color: #000;
            display: inline-block;
            margin-top: 10px;
        }
        .sidebar .nav-link.active {
            background-color: #007BFF;
            color: white;
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
    <div class="d-flex">
        <div class="sidebar d-flex flex-column">
            <h2 class="text-center">Acciones</h2>
            <hr>
            <nav class="nav flex-column">
                <a class="nav-link" href="#"><i class="bi bi-house"></i> Inicio</a>
                <a href="AdminU.php" class="nav-link" href="#"><i class="bi bi-speedometer2"></i> Administrar Usuario</a>
                <a class="nav-link" href="#"><i class="bi bi-table"></i> Gestionar </a>
                <a class="nav-link" href="#"><i class="bi bi-grid" ></i> Borrar</a>
            </nav>
            <div class="mt-auto text-center">
                <hr>
                <i class="fa-solid fa-user-tie"> Admin</i>
                <div class="container mt-2">
                    <a href="index.php"> Cerrar sesión</a>
                </div>
            </div>
        </div>
        <div class="p-4 flex-grow-1">
            <h1>Bienvenido</h1>
        </div>
    </div>
</body>
</html>