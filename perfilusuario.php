<?php
// perfil.php
include("conexion.php");
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.html");
    exit();
}

$nombre_usuario = $_SESSION['usuario'];
$message = "";

// Consultar la información del usuario
$query = "SELECT Nombre_usuario, Nombre, Cedula, Correo, Numero_telefonico, Dia_nacimiento FROM usuario WHERE Nombre_usuario = '$nombre_usuario'";
$result = mysqli_query($conexion, $query);

if (mysqli_num_rows($result) === 1) {
    $user_data = mysqli_fetch_assoc($result);
} else {
    echo "Error: Usuario no encontrado.";
    exit();
}

// Procesar el cambio de contraseña si el formulario es enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['contraseña_actual'], $_POST['nueva_contraseña'])) {
    $contraseña_actual = $_POST['contraseña_actual'];
    $nueva_contraseña = $_POST['nueva_contraseña'];

    // Verificar la contraseña actual
    $query_contraseña = "SELECT Contraseña FROM usuario WHERE Nombre_usuario = '$nombre_usuario'";
    $result_contraseña = mysqli_query($conexion, $query_contraseña);

    if ($result_contraseña && mysqli_num_rows($result_contraseña) === 1) {
        $user = mysqli_fetch_assoc($result_contraseña);

        if (password_verify($contraseña_actual, $user['Contraseña'])) {
            // Actualizar la contraseña
            $hashed_password = password_hash($nueva_contraseña, PASSWORD_DEFAULT);
            $update_query = "UPDATE usuario SET Contraseña = '$hashed_password' WHERE Nombre_usuario = '$nombre_usuario'";

            if (mysqli_query($conexion, $update_query)) {
                $message = "Contraseña actualizada correctamente.";
            } else {
                $message = "Error al actualizar la contraseña: " . mysqli_error($conexion);
            }
        } else {
            $message = "La contraseña actual es incorrecta.";
        }
    } else {
        $message = "Error: Usuario no encontrado.";
    }
}

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Usuario</title>
    <link rel="stylesheet" href="perfilusuario.css">
</head>
<body>
    <main>
        <h1>Perfil del Usuario</h1>
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <!-- Información del Usuario -->
        <form>
            <label>Nombre de Usuario:</label>
            <input type="text" value="<?php echo $user_data['Nombre_usuario']; ?>" readonly>

            <label>Nombre Completo:</label>
            <input type="text" value="<?php echo $user_data['Nombre']; ?>" readonly>

            <label>Cédula:</label>
            <input type="text" value="<?php echo $user_data['Cedula']; ?>" readonly>

            <label>Correo Electrónico:</label>
            <input type="email" value="<?php echo $user_data['Correo']; ?>" readonly>

            <label>Número Telefónico:</label>
            <input type="tel" value="<?php echo $user_data['Numero_telefonico']; ?>" readonly>

            <label>Fecha de Nacimiento:</label>
            <input type="date" value="<?php echo $user_data['Dia_nacimiento']; ?>" readonly>
        </form>

        <!-- Formulario para Cambiar Contraseña -->
        <h2>Cambiar Contraseña</h2>
        <form method="POST">
            <label>Contraseña Actual:</label>
            <input type="password" name="contraseña_actual" required>

            <label>Nueva Contraseña:</label>
            <input type="password" name="nueva_contraseña" required>

            <button type="submit">Actualizar Contraseña</button>
        </form>
    </main>
</body>
</html>

