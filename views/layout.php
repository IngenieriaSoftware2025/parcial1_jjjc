<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= asset('images/cit.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= asset('build/styles.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <title>Lista de Actividades</title>
    <style>
        .navbar {
            background: linear-gradient(135deg, #667eea, #764ba2) !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            border-bottom: 3px solid #74b9ff;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.3rem;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .navbar-brand img {
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
        }

        .navbar-brand:hover img {
            transform: rotate(10deg);
        }

        .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 0 5px;
            position: relative;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            color: #fff !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: #74b9ff;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 80%;
        }

        .navbar-toggler {
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .navbar-toggler:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: scale(1.1);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="/<?= $_ENV['APP_NAME'] ?>/">
            <img src="<?= asset('./images/cit.png') ?>" width="35px'" alt="cit">
            Lista de Actividades
        </a>
        <div class="collapse navbar-collapse" id="navbarToggler">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="margin: 0;">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/<?= $_ENV['APP_NAME'] ?>/"><i class="bi bi-house-fill me-2"></i>Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/<?= $_ENV['APP_NAME'] ?>/actividades"><i class="bi bi-cart-check me-2"></i>Actividades</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/<?= $_ENV['APP_NAME'] ?>/asistencias"><i class="bi bi-cart-check me-2"></i>Asistencias</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container pt-5 mb-4" style="min-height: 85vh">
    <?php echo $contenido; ?>
</main>

<footer class="bg-dark text-white py-3">
    <div class="container text-center">
        <p class="mb-0">Lista de Actividades &copy; <?= date('Y') ?></p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= asset('build/js/app.js') ?>"></script>
</body>
</html>
