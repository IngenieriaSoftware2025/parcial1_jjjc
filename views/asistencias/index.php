<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Asistencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #74b9ff, #0984e3);
            min-height: 100vh;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            border: none;
        }

        .btn {
            border-radius: 10px;
            font-weight: 500;
        }

        .btn-warning {
            background: linear-gradient(45deg, #fdcb6e, #e17055);
            border: none;
            color: white;
        }

        .btn-danger {
            background: linear-gradient(45deg, #fd79a8, #e84393);
            border: none;
        }

        .btn-secondary {
            background: linear-gradient(45deg, #636e72, #2d3436);
            border: none;
        }

        .btn-primary {
            background: linear-gradient(45deg, #74b9ff, #0984e3);
            border: none;
        }

        .btn-success {
            background: linear-gradient(45deg, #00b894, #00a085);
            border: none;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #ddd;
        }

        .form-control:focus, .form-select:focus {
            border-color: #74b9ff;
            box-shadow: 0 0 0 3px rgba(116, 185, 255, 0.25);
        }

        .table thead {
            background: linear-gradient(45deg, #74b9ff, #0984e3);
            color: white;
        }

        .text-primary {
            color: #2d3436 !important;
        }

        .nav-pills .nav-link {
            border-radius: 10px;
            margin: 0 5px;
            font-weight: 600;
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .badge-puntual {
            background: linear-gradient(45deg, #00b894, #00a085) !important;
        }

        .badge-tarde {
            background: linear-gradient(45deg, #fd79a8, #e84393) !important;
        }
    </style>
</head>
<body>
    <div class="container">
     

        <div class="row justify-content-center p-3">
            <div class="col-lg-10">
                <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #28a745;">
                    <div class="card-body p-3">
                        <div class="row mb-3">
                            <h5 class="text-center mb-2">¡Registra tu Asistencia Ahora!</h5>
                            <h4 class="text-center mb-2 text-success">REGISTRO DE ASISTENCIAS</h4>
                        </div>

                        <div class="row justify-content-center p-5 shadow-lg">
                            <form id="FormAsistencias">
                                <div class="row mb-3 justify-content-center">
                                    <div class="col-lg-6">
                                        <label for="actividad_id" class="form-label">SELECCIONAR ACTIVIDAD</label>
                                        <select name="actividad_id" class="form-select" id="actividad_id">
                                            <option value="" class="text-center"> -- SELECCIONE UNA ACTIVIDAD -- </option>
                                            <?php foreach($actividades as $a): ?>
                                                <option value="<?= $a->id ?>">
                                                    <?= $a->nombre ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3 justify-content-center">
                                    <div class="col-lg-6">
                                        <label for="fecha_asistencia" class="form-label">FECHA Y HORA DE ASISTENCIA</label>
                                        <input type="datetime-local" class="form-control" id="fecha_asistencia" name="fecha_asistencia" required>
                                    </div>
                                </div>

                                <div class="row justify-content-center mt-4">
                                    <div class="col-auto">
                                        <button class="btn btn-success btn-lg" type="submit" id="BtnRegistrarAsistencia">
                                            <i class="bi bi-check-circle me-2"></i>Registrar Asistencia
                                        </button>
                                    </div>

                                    <div class="col-auto">
                                        <button class="btn btn-secondary" type="reset" id="BtnLimpiarAsistencia">
                                            <i class="bi bi-arrow-clockwise me-1"></i>Limpiar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center p-3">
            <div class="col-lg-10">
                <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #28a745;">
                    <div class="card-body p-3">
                        <h5 class="text-center mb-3 text-success">FILTRAR ASISTENCIAS</h5>
                        <div class="row g-4">
                            <div class="col-md-3">
                                <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                                <input type="date" id="fecha_inicio" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label for="fecha_fin" class="form-label">Fecha de fin</label>
                                <input type="date" id="fecha_fin" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label for="actividad_filtro" class="form-label">Filtrar por Actividad</label>
                                <select id="actividad_filtro" class="form-select">
                                    <option value="">-- TODAS LAS ACTIVIDADES --</option>
                                    <?php foreach($actividades as $a): ?>
                                        <option value="<?= $a->id ?>"><?= $a->nombre ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button class="btn btn-primary w-100" id="btn_filtrar">
                                    <i class="bi bi-funnel-fill me-2"></i>Filtrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center p-3">
            <div class="col-lg-10">
                <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #00b894;">
                    <div class="card-body p-3">
                        <h3 class="text-center text-success">
                            <i class="bi bi-check-circle me-2"></i>ASISTENCIAS PUNTUALES
                        </h3>

                        <div class="table-responsive p-2">
                            <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TablaAsistenciasPuntuales">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center p-3">
            <div class="col-lg-10">
                <div class="card custom-card shadow-lg" style="border-radius: 10px; border: 1px solid #fd79a8;">
                    <div class="card-body p-3">
                        <h3 class="text-center text-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>LLEGADAS TARDE
                        </h3>

                        <div class="table-responsive p-2">
                            <table class="table table-striped table-hover table-bordered w-100 table-sm" id="TablaAsistenciasTarde">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="<?= asset('build/js/asistencias/index.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>