<?php 
session_start();
include '../Config/conexion.php';

// Registrar ingreso a AdminUsuarios en la bitácora SOLO UNA VEZ POR SESIÓN
if (isset($_SESSION['nick']) && isset($_SESSION['id_u']) && !isset($_SESSION['ingreso_ADMINUSUARIOS'])) {
    $accion = 'INGRESO A ADMINUSUARIOS';
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');
    $nick_sesion = $_SESSION['nick'];
    $id_u_sesion = $_SESSION['id_u'];

    $stmt_log = $conn->prepare("INSERT INTO bitacora (nick, fecha, hora, accion, id_u) VALUES (?, ?, ?, ?, ?)");
    $stmt_log->bind_param("ssssi", $nick_sesion, $fecha, $hora, $accion, $id_u_sesion);
    $stmt_log->execute();
    $stmt_log->close();

    // Marcar ingreso registrado en la sesión
    $_SESSION['ingreso_ADMINUSUARIOS'] = true;
}

// Obtener usuarios
$sql = "SELECT 
            u.id_u, 
            u.Nick, 
            p.Nombre AS Perfil, 
            u.borrado,
            (SELECT per.nombre FROM persona per WHERE per.id_u = u.id_u) AS nombre,
            (SELECT per.edad FROM persona per WHERE per.id_u = u.id_u) AS edad
        FROM usuario u 
        LEFT JOIN perfil p ON u.id_p = p.id_p";
$result = $conn->query($sql);
$usuarios = $result->fetch_all(MYSQLI_ASSOC);
?>



<!DOCTYPE html>
<html>
<head>
    <title>Administrar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/fontawesome-free-6.7.2-web/css/all.css">
</head>
<style>
    a {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background-color: rgb(255, 0, 0);
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }
    a:hover {
        background-color: rgb(179, 0, 0);
    }
    .boton-agregar {
    display: inline-block;
    margin-top: 10px;
    margin-bottom: 20px;
    padding: 10px 10px;
    background-color: rgb(60, 255, 0);
    color: white;
    text-decoration: none;
    border-radius: 5px;
    }

    .boton-agregar:hover {
        background-color: rgb(5, 95, 12);
    }
</style>
<body class="bg-gray-100 p-8">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-2xl font-bold text-green-600 mb-4 text-center">Administrar Usuarios</h2>
        
            <!-- Tabla de usuarios -->
            <div class="bg-white p-6 rounded shadow-md">
                <h3 class="text-xl font-semibold text-green-600 mb-4">Usuarios Registrados</h3>
                <a href="../AdmUsuarios/Adminu.html"  class="boton-agregar"> Agregar Usuario</a>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">ID</th>
                                <th scope="col" class="px-6 py-3">Nombre</th>
                                <th scope="col" class="px-6 py-3">Edad</th>
                                <th scope="col" class="px-6 py-3">Nick</th>
                                <th scope="col" class="px-6 py-3">Perfil</th>
                                <th scope="col" class="px-6 py-3">Editar</th>
                                <th scope="col" class="px-6 py-3">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $row) { ?>
                            <tr class="bg-white border-b">
                                
                                <td class="px-6 py-4"> <?php echo $row['id_u']; ?> </td>
                                <td class="px-6 py-4"> <?php echo $row['nombre']; ?> </td>
                                <td class="px-6 py-4"> <?php echo $row['edad']; ?> </td>
                                <td class="px-6 py-4"> <?php echo $row['Nick']; ?> </td>
                                <td class="px-6 py-4"> <?php echo $row['Perfil'] ? $row['Perfil'] : 'Sin perfil'; ?> </td>
                                <td class="text-center">
                                    <form action="../AdmUsuarios/actualizar.php" method="GET">
                                        <input type="hidden" name="id" value="<?php echo $row['id_u']; ?>">
                                        <button type="submit" class="btn btn-warning">
                                        <i class="fa-solid fa-user-pen"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <form action="../AdmUsuarios/activar.php" method="POST">
                                        <input type="hidden" name="id" value="<?php echo $row['id_u']; ?>">
                                        <button type="submit" class="btn <?php echo ($row['borrado'] == 1) ?>">
                                            <?php echo ($row['borrado'] == 1) ? '<i class="fa-solid fa-user-slash" href="con_bitacora.php?accion=HABILITO_USUARIO">
                                            </i>' : '<i class="fa-solid fa-user" href="con_bitacora.php?accion=DESHABILITO_USUARIO"></i>'; ?>
                                        </button>
                                    </form>
                                </td>

                            </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="container mt-2"><a href="../Administrador/Admin.html">Volver</a></div>
    </div>
</body>
</html>