<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Agregar mascotas</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!--google fonts-->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">

        <!-- CSS -->
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/login.css">
    </head>
    <body>

        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg bg-white shadow-sm">
            <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png" alt="logo" width="30" class="me-2">
                Marca
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="info.html">Peticiones</a></li>
                <li class="nav-item"><a class="nav-link" href="adoptar.html">Reportes</a></li>
                <li class="nav-item"><a class="nav-link" href="agregar_mascota.html">Agregar mascotas</a></li>
                <li class="nav-item"><a class="nav-link" href="reporte.html">Reportar</a></li>
                <li class="nav-item"><a class="nav-link" href="adoptar.html">Catalogo</a></li>
                </ul>
                <a href="login.html" class="btn btn-outline-dark ms-3">Login</a>
            </div>
            </div>
        </nav>

        <div class="principal">
            <div class="contc">
                    Agregar mascotas
            </div>
            <div class="formulario">
                <form>
                    <div class="mb-3">
                        <label for="formGroupExampleInput" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Placeholder">
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput" class="form-label">Especie</label>
                        <select id="especie" class="form-select" aria-label="Default select example">
                        <option selected>Seleccionar especie</option>
                        <option value="perro">Perro</option>
                        <option value="gato">Gato</option>
                    </select>
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput2" class="form-label">Edad</label>
                        <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Placeholder">
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput2" class="form-label">Raza</label>
                        <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Placeholder">
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput" class="form-label">Tamaño</label>
                        <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Placeholder">
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput2" class="form-label">Sexo</label>
                        <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Placeholder">
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput2" class="form-label">Color</label>
                        <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Placeholder">
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput2" class="form-label">Color secundario</label>
                        <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Placeholder">
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput2" class="form-label">Comportamiento</label>
                        <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Placeholder">
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput2" class="form-label">Convivencia</label>
                        <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Placeholder">
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput2" class="form-label">Patrón de pelo</label>
                        <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Placeholder">
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput2" class="form-label">Tipo de pelo</label>
                        <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Placeholder">
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput2" class="form-label">Padecimientos</label>
                        <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Placeholder">
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput2" class="form-label">Vacunas</label>
                        <div class="form-check perro">
                            <input class="form-check-input" type="checkbox" id="rabiaPerro">
                            <label class="form-check-label" for="rabiaPerro">Rabia</label>
                        </div>
                        <div class="form-check perro">
                            <input class="form-check-input" type="checkbox" id="parvovirus">
                            <label class="form-check-label" for="parvovirus">Parvovirus</label>
                        </div>
                        <div class="form-check perro">
                            <input class="form-check-input" type="checkbox" id="moquillo">
                            <label class="form-check-label" for="moquillo">Moquillo</label>
                        </div>
                        <div class="form-check perro">
                            <input class="form-check-input" type="checkbox" id="hepatitis">
                            <label class="form-check-label" for="hepatitis">Hepatitis</label>
                        </div>
                        <div class="form-check perro">
                            <input class="form-check-input" type="checkbox" id="leptospirosis">
                            <label class="form-check-label" for="leptospirosis">Leptospirosis</label>
                        </div>
                        <div class="form-check perro">
                            <input class="form-check-input" type="checkbox" id="bordetella">
                            <label class="form-check-label" for="bordetella">Bordetella</label>
                        </div>

                        <!-- Vacunas de gato -->
                        <div class="form-check gato">
                            <input class="form-check-input" type="checkbox" id="rabiaGato">
                            <label class="form-check-label" for="rabiaGato">Rabia</label>
                        </div>
                        <div class="form-check gato">
                            <input class="form-check-input" type="checkbox" id="tripleFelina">
                            <label class="form-check-label" for="tripleFelina">Triple felina</label>
                        </div>
                        <div class="form-check gato">
                            <input class="form-check-input" type="checkbox" id="leucemia">
                            <label class="form-check-label" for="leucemia">Leucemia felina</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput2" class="form-label">Tratamiento</label>
                        <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Placeholder">
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput2" class="form-label">Señas particulares</label>
                        <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Placeholder">
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput2" class="form-label">Foto</label>
                        <input class="form-control" type="file" id="formFile">
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput2" class="form-label">RUAC</label>
                        <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Placeholder">
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput" class="form-label">Chip</label>
                        <select class="form-select" aria-label="Default select example">
                        <option selected>Seleccionar opción</option>
                        <option value="1">Sí</option>
                        <option value="2">No</option>
                    </select>
                    <div class="mb-3">
                        <label for="formGroupExampleInput" class="form-label">Tipo de chip</label>
                        <select class="form-select" aria-label="Default select example">
                        <option selected>Seleccionar opción</option>
                        <option value="1">RFID</option>
                        <option value="2">NFC</option>
                        <option value="3">Ninguno</option>
                    </select>
                    </div>
                    <div class="mb-3">
                        <label for="formGroupExampleInput2" class="form-label">Número de chip</label>
                        <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Placeholder">
                    </div>
                    <button type="button" class="btn btn-dark" id="env">Registrar mascota</button>
                </form>
            </div>
        </div>
    </body>
    <!-- pie pagina -->
    <footer>
        MURASAKI 2026. ©
    </footer>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Script -->
    <script src="script.js"></script>
    <script src="../js/agregar_mascota.js"></script>
</html>