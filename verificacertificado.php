<?php
// Incluir el archivo de conexión
include("conexion.php");

// Iniciar sesión
session_start();

$message = ""; // Variable para almacenar mensajes

// Verificar si el método de solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que todos los campos requeridos están presentes
    if (isset($_POST['username'], $_POST['codigo_certificado'], $_POST['universidad'])) {
        // Sanitizar y escapar los datos del formulario
        $username = mysqli_real_escape_string($conexion, $_POST['username']);
        $codigo_certificado = mysqli_real_escape_string($conexion, $_POST['codigo_certificado']);
        $universidad = mysqli_real_escape_string($conexion, $_POST['universidad']);

        // Insertar la solicitud en la base de datos con el estado inicial 'pendiente'
        $sql = "INSERT INTO solicitudes_impresion (username, codigo_certificado, universidad, estado) 
                VALUES ('$username', '$codigo_certificado', '$universidad', 'pendiente')";

        if (mysqli_query($conexion, $sql)) {
            $message = "Solicitud registrada correctamente. Un administrador la procesará pronto.";
        } else {
            $message = "Error al registrar la solicitud: " . mysqli_error($conexion);
        }
    } else {
        $message = "Por favor, complete todos los campos del formulario.";
    }

    // Cerrar la conexión
    mysqli_close($conexion);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Validación de Certificado</title>
  <link rel="stylesheet" href="verifcacertificado.css">
</head>
<body>
  <header>
    <div class="header-container">
      <img src="/imagenes2/logo2.jpeg" alt="Logo" class="logo">
      <a href="estudiantes.html" class="menu">Inicio</a>
    </div>
  </header>

  <main>
    <section class="contenido">
      <div class="formulario-contenedor">
        <!-- Formulario de validación -->
        <form action="" method="POST" class="formulario">
          <h2>Valida tu Certificado</h2>
          <label for="username">Nombre de Usuario</label>
          <input type="text" id="username" name="username" placeholder="Username" required>

          <label for="codigo">Código del certificado</label>
          <input type="text" id="codigo" name="codigo_certificado" placeholder="Código" required>

          <label for="universidad">Universidad</label>
          <input type="text" id="universidad" name="universidad" placeholder="Nombre de la universidad" required>

          <div class="botones">
            <button type="submit" class="enviar">Validar</button>
            <button type="reset" class="cancelar">Cancelar</button>
          </div>
        </form>

        <!-- Imagen del certificado -->
        <div class="imagen-certificado">
          <img src="/imagenes2/ppp.jfif" alt="Vista previa del certificado" class="certificado">
        </div>
      </div>

      <!-- Mensaje dinámico -->
      <?php if (!empty($message)): ?>
        <div class="mensaje">
          <p><?php echo htmlspecialchars($message); ?></p>
        </div>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>
