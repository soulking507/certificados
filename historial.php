<?php
// Incluir el archivo de conexión
include("conexion.php");

// Iniciar sesión
session_start();

// Consultar las solicitudes en la base de datos
$sql = "SELECT id_solicitud, username, codigo_certificado, universidad, estado, fecha_solicitud FROM solicitudes_impresion";
$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Historial de Peticiones</title>
  <link rel="stylesheet" href="historial.css">
</head>
<body>
  <header>
    <div class="logo">
      <img src="/imagenes2/logo2.jpeg" alt="Logo" class="logo-img">
  </div>
    <div class="header-container">
      <a href="estudiantes.html"><span class="menu">Inicio</span></a>
    </div>
  </header>

  <main>
    <section class="contenido">
      <h2>Historial</h2>
      <table>
        <thead>
          <tr>
            <th>ID Solicitud</th>
            <th>Usuario</th>
            <th>Código de Certificado</th>
            <th>Universidad</th>
            <th>Estado</th>
            <th>Fecha y Hora</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Verificar si hay resultados
          if (mysqli_num_rows($resultado) > 0) {
              // Recorrer los resultados y generar las filas de la tabla
              while ($fila = mysqli_fetch_assoc($resultado)) {
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($fila['id_solicitud']) . "</td>";
                  echo "<td>" . htmlspecialchars($fila['username']) . "</td>";
                  echo "<td>" . htmlspecialchars($fila['codigo_certificado']) . "</td>";
                  echo "<td>" . htmlspecialchars($fila['universidad']) . "</td>";
                  echo "<td>" . htmlspecialchars($fila['estado']) . "</td>";
                  echo "<td>" . htmlspecialchars($fila['fecha_solicitud']) . "</td>";
                  echo "</tr>";
              }
          } else {
              echo "<tr><td colspan='6'>No hay solicitudes registradas.</td></tr>";
          }

          // Cerrar la conexión a la base de datos
          mysqli_close($conexion);
          ?>
        </tbody>
      </table>
    </section>
  </main>
</body>
</html>
