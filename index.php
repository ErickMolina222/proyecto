<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Winky+Sans:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Winky Sans', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 50px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
        }
        .input-box {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-btn {
            font-family: 'Winky Sans', sans-serif;
            width: 108%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .login-btn:hover {
            background-color: rgb(99, 255, 51);
        }
        .error {
            background-color: #ffcccc;
            color: #990000;
            padding: 10px;
            border: 1px solid #ff6666;
            border-radius: 5px;
            display: inline-block;
        }
    </style>

<script>
function validarUsuario(input) {
    const regex = /^[a-zA-Z0-9]+$/;  // Permitir letras y números
    const valoresProhibidos = ["admin", "root", "test"]; 
    const mensajeError = document.getElementById('mensajeError');

    // Limpiar caracteres no permitidos
    input.value = input.value.replace(/[^a-zA-Z0-9]/g, '');

    // Validar si el valor ingresado está en la lista de prohibidos
    if (valoresProhibidos.includes(input.value.toLowerCase())) {
        mensajeError.textContent = 'El nombre de usuario ingresado no está permitido.';
        input.value = ''; // Borra el campo
    } else {
        mensajeError.textContent = '';
    }
}
</script>
</head>
<body>

    <div class="login-container">
        <h2>Iniciar Sesión</h2>

        <?php if (isset($_GET['error'])): ?>
            <p class="error">
                <?php echo ($_GET['error'] == 1) ? "Credenciales incorrectas" : "Campos vacíos"; ?>
            </p>
        <?php endif; ?>

        <form method="post" action="controlador.php">
            <input type="text" id="usuario" name="usuario" placeholder="Usuario" class="input-box" required oninput="validarUsuario(this)">
            
            <input type="password" id="contraseña" name="contraseña" placeholder="Contraseña" class="input-box" required>
            <button type="submit" class="login-btn">Iniciar Sesión</button>
        </form>

        <p id="mensajeError" style="color:red;"></p>
    </div>

</body>

</html>
