<?php
// Incluir el archivo de conexión
include("conexion.php");

// Iniciar sesión
session_start();

$message = ""; // Variable para almacenar mensajes

// Verificar si el método de solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cert-code'], $_POST['action'])) {
        // Sanitizar y escapar los datos del formulario
        $codigo_certificado = mysqli_real_escape_string($conexion, $_POST['cert-code']);
        $action = $_POST['action'];

        // Actualizar el estado según la acción seleccionada (aprobar o denegar)
        $nuevo_estado = $action === 'aprobar' ? 'procesada' : 'rechazado';

        $sql = "UPDATE solicitudes_impresion SET estado='$nuevo_estado' WHERE codigo_certificado='$codigo_certificado'";

        if (mysqli_query($conexion, $sql)) {
            $message = $action === 'aprobar' 
                ? "El certificado ha sido aprobado." 
                : "El certificado ha sido rechazado.";
        } else {
            $message = "Error al actualizar el estado: " . mysqli_error($conexion);
        }
    } else {
        $message = "Por favor, complete el formulario correctamente.";
    }
}

// Consultar la base de datos para mostrar información del certificado (opcional)
$certificado = null;
if (isset($_GET['codigo_certificado'])) {
    $codigo_certificado = mysqli_real_escape_string($conexion, $_GET['codigo_certificado']);
    $result = mysqli_query($conexion, "SELECT * FROM solicitudes_impresion WHERE codigo_certificado='$codigo_certificado'");
    if ($result) {
        $certificado = mysqli_fetch_assoc($result);
    }
}

// Cerrar la conexión
mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprobación de Certificados</title>
    <link rel="stylesheet" href="validacerti.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="/imagenes2/logo2.jpeg" alt="Logo" class="logo-img">
        </div>
        <nav>
            <a href="administrador.html">Inicio</a>
        </nav>
    </header>
    <main>
        <section class="approval-section">
            <h1>Aprobación o denegación de impresión de certificado</h1>
            <p><?php echo $message; ?></p>
            <form class="approval-form" method="POST" action="">
                <label for="cert-code">Código del certificado</label>
                <input type="text" id="cert-code" name="cert-code" placeholder="Código" required>
                <div class="buttons">
                    <button type="submit" name="action" value="aprobar" class="btn-approve">Aprobar</button>
                    <button type="submit" name="action" value="denegar" class="btn-deny">Denegar</button>
                </div>
            </form>
        </section>
        <section class="certificate-section">
    <h2>Certificado</h2>
    <img src="imagenes2/ppp.jfif" alt="Certificado" class="certificate-img">
</section>

    </main>
</body>
</html>
