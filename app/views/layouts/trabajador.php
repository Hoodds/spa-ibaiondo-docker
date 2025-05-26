<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Trabajador - Spa Ibaiondo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= Helper::asset('css/styles.css') ?>">
</head>
<body class="d-flex flex-column min-vh-100 fixed-navbar-body">
    <nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="<?= Helper::url('trabajador/dashboard') ?>">
                <strong>TRABAJADOR SPA IBAIONDO</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if (isset($_SESSION['trabajador']) && isset($_SESSION['trabajador_rol'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= Helper::url('trabajador/dashboard') ?>">Dashboard</a>
                        </li>
                        <?php if ($_SESSION['trabajador_rol'] === 'recepcionista'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= Helper::url('trabajador/reservas') ?>">Reservas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= Helper::url('trabajador/valoraciones') ?>">Valoraciones</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= Helper::url('trabajador/reservas') ?>">Mis Reservas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= Helper::url('trabajador/valoraciones') ?>">Mis Valoraciones</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['trabajador'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= Helper::url('trabajador/logout') ?>">Cerrar Sesión</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= Helper::url('trabajador/login') ?>">Iniciar Sesión</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show">
                <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show">
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <main class="flex-fill">
        <?= $content ?? '' ?>
    </main>
    <?php include BASE_PATH . '/app/views/layouts/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= Helper::asset('js/main.js') ?>"></script>
</body>
</html>

