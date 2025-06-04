<?php 
include 'conexion.php';


// Agregar usuario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_u= $_POST['id_u'];
    $nombre = $_POST['nombre'];
    $edad = $_POST['edad'];
    $nick = $_POST['nick'];
    $pwd = $_POST['pwd'];
    $perfil = $_POST['perfil'];

    $sql = "INSERT INTO usuario (id_u, Nombre, Edad, Nick, Pwd, id_p) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isissi", $id_u, $nombre, $edad, $nick, $pwd, $perfil);
    $stmt->execute();
    $stmt->close();
}

// Obtener usuarios
$sql = "SELECT u.id_u, u.Nombre, u.Edad, u.Nick, p.Nombre AS Perfil FROM usuario u 
        LEFT JOIN perfil p ON u.id_p = p.id_p WHERE u.Borrado = '0'";
$result = $conn->query($sql);
$usuarios = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Administrar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./public/fontawesome-free-6.7.2-web/css/all.css">
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
</style>
<body class="bg-gray-100 p-8">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-2xl font-bold text-green-600 mb-4 text-center">Administrar Usuarios</h2>
        
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Formulario -->
            <div class="bg-white p-6 rounded shadow-md">
                <h3 class="text-xl font-semibold text-green-600 mb-4">Agregar Usuario</h3>
                <form method="post">
                    <label>Id_u</label>
                    <input type="text" name="id_u" class="w-full p-2 border rounded mb-4" required>
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="w-full p-2 border rounded mb-4" required>

                    <label>Edad</label>
                    <input type="number" name="edad" class="w-full p-2 border rounded mb-4" required>

                    <label>Nick</label>
                    <input type="text" name="nick" class="w-full p-2 border rounded mb-4" required>

                    <label>Contraseña</label>
                    <input type="password" name="pwd" class="w-full p-2 border rounded mb-4" required>

                    <label>Perfil</label>
                    <select name="perfil" class="w-full p-2 border rounded mb-4" required>
                        <option value="1">Estudiante</option>
                        <option value="2">Administrador</option>
                        <option value="3">Profesor</option>
                    </select>

                    <button type="submit" class="w-full bg-green-500 text-white p-2 rounded hover:bg-green-600">Agregar Usuario</button>
                </form>
            </div>

            <!-- Tabla de usuarios -->
            <div class="bg-white p-6 rounded shadow-md">
                <h3 class="text-xl font-semibold text-green-600 mb-4">Usuarios Registrados</h3>
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
                                <td class="px-6 py-4"> <?php echo $row['Nombre']; ?> </td>
                                <td class="px-6 py-4"> <?php echo $row['Edad']; ?> </td>
                                <td class="px-6 py-4"> <?php echo $row['Nick']; ?> </td>
                                <td class="px-6 py-4"> <?php echo $row['Perfil'] ? $row['Perfil'] : 'Sin perfil'; ?> </td>
                                <td class="text-center">
                                    <form action="actualizar.php" method="GET">
                                        <input type="hidden" name="id" value="<?php echo $row['id_u']; ?>">
                                        <button type="submit" class="btn btn-warning">
                                        <i class="fa-solid fa-user-pen"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center">
										<form action="./eliminar.php" method="POST">
											<button class="btn btn-danger">
                                                <i class="fa-solid fa-user-slash"></i>
											</button>
										</form>
								</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="container mt-2"><a href="Admin.php">Volver</a></div>
    </div>
</body>
</html>