<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spa Ibaiondo - Bienestar y Relajación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= Helper::asset('css/styles.css') ?>?v=<?= time() ?>">
    </head>
<body class="d-flex flex-column min-vh-100 fixed-navbar-body">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary text-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="<?= Helper::url('') ?>">
                <strong>SPA IBAIONDO</strong>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Helper::url('') ?>">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Helper::url('servicios') ?>">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Helper::url('contacto') ?>">Contacto</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (Auth::check()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?= Helper::e(Auth::user()['nombre']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= Helper::url('perfil') ?>">Mi Perfil</a></li>
                                <li><a class="dropdown-item" href="<?= Helper::url('reservas') ?>">Mis Reservas</a></li>
                                <?php if (Auth::isAdmin()): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= Helper::url('admin') ?>">Panel Admin</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= Helper::url('logout') ?>">Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= Helper::url('login') ?>">Iniciar Sesión</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= Helper::url('registro') ?>">Registrarse</a>
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
</body>
</html>

