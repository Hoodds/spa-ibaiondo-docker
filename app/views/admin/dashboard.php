<div class="container-fluid py-4">
    <h1 class="mb-4">Panel de Administración</h1>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Usuarios</h5>
                            <h2 class="mb-0"><?= $stats['usuarios'] ?></h2>
                        </div>
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?= Helper::url('admin/usuarios') ?>" class="text-white">Ver detalles <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Trabajadores</h5>
                            <h2 class="mb-0"><?= $stats['trabajadores'] ?></h2>
                        </div>
                        <i class="fas fa-user-tie fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?= Helper::url('admin/trabajadores') ?>" class="text-white">Ver detalles <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Servicios</h5>
                            <h2 class="mb-0"><?= $stats['servicios'] ?></h2>
                        </div>
                        <i class="fas fa-spa fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?= Helper::url('admin/servicios') ?>" class="text-white">Ver detalles <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Reservas</h5>
                            <h2 class="mb-0"><?= $stats['reservas'] ?></h2>
                        </div>
                        <i class="fas fa-calendar-check fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?= Helper::url('admin/reservas') ?>" class="text-white">Ver detalles <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Valoraciones</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3">
                                <h3 class="text-primary"><?= $stats['valoraciones']['total'] ?? 0 ?></h3>
                                <p class="mb-0">Total</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3">
                                <h3 class="text-warning"><?= $stats['valoraciones']['por_estado']['pendiente'] ?? 0 ?></h3>
                                <p class="mb-0">Pendientes</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-3">
                                <h3 class="text-success"><?= $stats['valoraciones']['media_global'] ?? 0 ?></h3>
                                <p class="mb-0">Puntuación Media</p>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($stats['valoraciones']['mejores_servicios'])): ?>
                        <h6 class="mt-4">Servicios Mejor Valorados</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Servicio</th>
                                        <th>Puntuación</th>
                                        <th>Valoraciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats['valoraciones']['mejores_servicios'] as $servicio): ?>
                                        <tr>
                                            <td><?= Helper::e($servicio['nombre']) ?></td>
                                            <td>
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <?php if ($i <= round($servicio['media'])): ?>
                                                        <i class="fas fa-star text-warning"></i>
                                                    <?php else: ?>
                                                        <i class="far fa-star text-warning"></i>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                                (<?= round($servicio['media'], 1) ?>)
                                            </td>
                                            <td><?= $servicio['total'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        <a href="<?= Helper::url('admin/valoraciones') ?>" class="btn btn-sm btn-outline-primary">Ver Todas</a>
                        <a href="<?= Helper::url('admin/valoraciones/pendientes') ?>" class="btn btn-sm btn-warning">
                            Pendientes
                            <?php if (isset($stats['valoraciones']['por_estado']['pendiente']) && $stats['valoraciones']['por_estado']['pendiente'] > 0): ?>
                                <span class="badge bg-danger"><?= $stats['valoraciones']['por_estado']['pendiente'] ?></span>
                            <?php endif; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Reservas Recientes</h5>
                </div>
                <div class="card-body">
                    <!-- Aquí se podría mostrar un listado de las reservas más recientes -->
                    <p class="text-center text-muted">Próximamente: Gráfico de reservas por día/semana</p>
                </div>
            </div>
        </div>
    </div>
</div>

