<?php
session_start();
include("../conexion.php");

$logueado = isset($_SESSION['id_usuario']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reportes | Paw Rescue</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-light">

<!-- ================= NAVBAR ================= -->
   <?php include 'navbar.php'; ?>

<!-- ================= CONTENIDO ================= -->
<div class="container my-5">

  <h2 class="text-center mb-4">Reportes de Perros</h2>

  <!-- BOTÓN CONTROLADO -->
  <div class="text-center mb-4">
    <?php if ($logueado): ?>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalReporte">
        Reportar mascota
      </button>
    <?php else: ?>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalLogin">
        Reportar mascota
      </button>
    <?php endif; ?>
  </div>

  <!-- ================= LISTA DE REPORTES ================= -->
  <?php
  $sql = "
    SELECT r.nombre, r.descripcion, r.ubicacion, r.foto, r.fecha,
           e.nombre AS estatus
    FROM paw_rescue.reporte_animal r
    JOIN paw_rescue.estatus_reporte e ON r.id_estatus = e.id_estatus
    ORDER BY r.fecha DESC
  ";

  $result = pg_query($conexion, $sql);

  while ($row = pg_fetch_assoc($result)):
    $color = match ($row['estatus']) {
      'Rescatado' => 'success',
      'No rescatado' => 'danger',
      default => 'warning'
    };
  ?>
    <div class="card mb-4 shadow-sm">
      <div class="row g-0">
        <div class="col-md-4">
          <img src="<?= $row['foto'] ? '../imgReportes/'.htmlspecialchars($row['foto']) : '../img/perro.jpeg' ?>"
               class="img-fluid rounded-start">
        </div>

        <div class="col-md-8">
          <div class="card-body">
            <h5><?= $row['nombre'] ?: 'Perro sin nombre' ?></h5>

            <p><?= nl2br(htmlspecialchars($row['descripcion'])) ?></p>

            <p class="text-muted">📍 <?= htmlspecialchars($row['ubicacion']) ?></p>
            <p class="text-muted">🕒 <?= date("d/m/Y H:i", strtotime($row['fecha'])) ?></p>

            <span class="badge bg-<?= $color ?>">
              <?= htmlspecialchars($row['estatus']) ?>
            </span>
          </div>
        </div>
      </div>
    </div>
  <?php endwhile; ?>

</div>

<!-- ================= MODAL REPORTE ================= -->
<div class="modal fade" id="modalReporte" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Reportar perro</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="guardarReporte.php" method="POST" enctype="multipart/form-data">
        <div class="modal-body">

          <div class="mb-3">
            <label class="form-label">Nombre (opcional)</label>
            <input type="text" name="nombre" class="form-control">
          </div>

          <div class="mb-3">
            <label class="form-label">Situación</label>
            <select name="situacion" class="form-select" required>
              <option value="">Selecciona</option>
              <option value="calle">En la calle</option>
              <option value="abandono">Abandono</option>
              <option value="maltrato">Maltrato</option>
            </select>
          </div>

          <!-- ✅ HERIDO CLARO -->
          <div class="mb-3">
            <label class="form-label">¿Está herido?</label>
            <select name="herido" class="form-select" required>
              <option value="NO" selected>No</option>
              <option value="SI">Sí</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Descripción de heridas</label>
            <textarea name="descripcion_heridas" class="form-control"></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Descripción general</label>
            <textarea name="descripcion" class="form-control" required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Ubicación</label>
            <input type="text" name="ubicacion" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Foto</label>
            <input type="file" name="foto" class="form-control">
          </div>

        </div>

        <div class="modal-footer">
          <button class="btn btn-success w-100">Publicar reporte</button>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- ================= MODAL LOGIN ================= -->
<div class="modal fade" id="modalLogin" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <h5>Debes iniciar sesión</h5>
      <p>Para reportar una mascota necesitas estar registrado.</p>
      <a href="login.php" class="btn btn-primary w-100 mb-2">Iniciar sesión</a>
      <a href="registro.php" class="btn btn-outline-secondary w-100">Registrarse</a>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
