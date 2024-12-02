<?php
include("conexion.php");
session_start();

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener la acción enviada desde el formulario
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'register') {
            // Procesar el formulario de registro
            if (isset($_POST['nombre_usuario'], $_POST['nombre_completo'], $_POST['cedula'], $_POST['correo'], $_POST['numero_telefonico'], $_POST['dia_nacimiento'], $_POST['contraseña'])) {
                $nombre_usuario = $_POST['nombre_usuario'];
                $nombre_completo = $_POST['nombre_completo'];
                $cedula = $_POST['cedula'];
                $correo = $_POST['correo'];
                $numero_telefonico = $_POST['numero_telefonico'];
                $dia_nacimiento = $_POST['dia_nacimiento'];
                $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);

                // Definir el valor de $id_cargo (por ejemplo, 2 para Estudiante)
                $id_cargo = 2;

                // Asegurarse de que la columna 'id_cargo' exista en la base de datos
                $register_query = "INSERT INTO usuario (Nombre_usuario, Nombre, Cedula, Correo, Numero_telefonico, Dia_nacimiento, Contraseña, id_cargo) 
                                   VALUES ('$nombre_usuario', '$nombre_completo', '$cedula', '$correo', '$numero_telefonico', '$dia_nacimiento', '$contraseña', '$id_cargo')";

                if (mysqli_query($conexion, $register_query)) {
                } else {
                    $message = "Error: " . mysqli_error($conexion);
                }
            } else {
                $message = "Por favor, complete todos los campos de registro.";
            }

        } elseif ($action === 'login') {
            // Procesar el formulario de login
            if (isset($_POST['correo'], $_POST['contraseña'])) {
                $correo = $_POST['correo'];
                $contraseña = $_POST['contraseña'];

                // Consultar el usuario en la base de datos
                $login_query = "SELECT * FROM usuario WHERE Correo = '$correo'";
                $result = mysqli_query($conexion, $login_query);

                if (mysqli_num_rows($result) === 1) {
                    $user = mysqli_fetch_assoc($result);

                    // Verificar la contraseña
                    if (password_verify($contraseña, $user['contraseña'])) {
                        $_SESSION['usuario'] = $user['Nombre_usuario']; // Iniciar sesión con el nombre de usuario
                        $_SESSION['id_cargo'] = $user['id_cargo']; // Almacenar el id_cargo del usuario

                        // Redirigir según el id_cargo
                        if ($user['id_cargo'] == 1) {
                            header("Location: administrador.html"); // Redirigir al área de administrador
                        } else if ($user['id_cargo'] == 2) {
                            header("Location: estudiantes.html"); // Redirigir al área de estudiante
                        }
                        exit();
                    } else {
                        $message = "Contraseña incorrecta.";
                    }
                } else {
                    $message = "El correo no está registrado.";
                }
            } else {
                $message = "Por favor, ingrese el correo y la contraseña.";
            }
        }
    }
}

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .hidden {
            display: none;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        input, button {
            padding: 10px;
        }

        .register-form {
            margin-top: 20px;
        }

        .message {
            margin: 10px 0;
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="image-section">
            <img src="imagenes2/llplplpl.jfif" alt="Graduation" class="graduation-image">
        </div>
        <div class="form-section">
            <div class="logo">
                <img src="imagenes2/logo2.jpeg" alt="Logo" class="logo-img">
            </div>

            <!-- Mostrar mensaje dinámico -->
            <?php if (!empty($message)): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>

            <!-- Formulario de Login -->
            <form class="login-form" id="loginForm" method="POST" action="portal.php">
                <input type="hidden" name="action" value="login">
                <div class="form-container">
                    <input type="email" name="correo" placeholder="Email" required>
                    <input type="password" name="contraseña" placeholder="Password" required>
                </div>
                <button type="submit" class="sign-in-btn">Sign In</button>
                <button type="button" class="register-btn" id="registerButton">Register</button>
            </form>

            <!-- Formulario de Registro (oculto al inicio) -->
            <form class="register-form hidden" id="registerForm" method="POST" action="portal.php">
                <input type="hidden" name="action" value="register">
                <div class="form-container">
                    <input type="text" name="nombre_usuario" placeholder="Nombre de Usuario" required>
                    <input type="text" name="nombre_completo" placeholder="Nombre Completo" required>
                    <input type="text" name="cedula" placeholder="Cédula" required>
                    <input type="email" name="correo" placeholder="Correo" required>
                    <input type="tel" name="numero_telefonico" placeholder="Número Telefónico" required>
                    <input type="date" name="dia_nacimiento" placeholder="Fecha de Nacimiento" required>
                    <input type="password" name="contraseña" placeholder="Contraseña" required>
                </div>
                <button type="submit" class="confirm-btn">Confirmar</button>
                <button type="button" class="cancel-btn" id="cancelButton">Cancelar</button>
            </form>
        </div>
    </div>

    <script>
        // Seleccionar los elementos
        const registerButton = document.getElementById('registerButton');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const cancelButton = document.getElementById('cancelButton');

        // Mostrar el formulario de registro y ocultar el de login
        registerButton.addEventListener('click', () => {
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
        });

        // Cancelar el registro y volver al login
        cancelButton.addEventListener('click', () => {
            registerForm.classList.add('hidden');
            loginForm.classList.remove('hidden');
        });
    </script>
</body>
</html>
