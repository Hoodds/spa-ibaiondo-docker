<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="mb-3">Bienvenido/a, <?= Helper::e($_SESSION['trabajador_nombre']) ?></h1>
                    <p class="text-muted">Panel de recepcionista de Spa Ibaiondo - <?= date('d/m/Y') ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-primary text-white shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Reservas Pendientes</h5>
                    <h2 class="display-4">
                        <?= count(array_filter($reservas, function($r) { return $r['estado'] === 'pendiente'; })) ?>
                    </h2>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?= Helper::url('trabajador/reservas?filtroEstado=pendiente') ?>" class="text-white">Ver detalles <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-success text-white shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Reservas Confirmadas</h5>
                    <h2 class="display-4">
                        <?= count(array_filter($reservas, function($r) { return $r['estado'] === 'confirmada'; })) ?>
                    </h2>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?= Helper::url('trabajador/reservas?filtroEstado=confirmada') ?>" class="text-white">Ver detalles <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-warning shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Valoraciones Pendientes</h5>
                    <h2 class="display-4">
                        <?= count(array_filter($valoraciones, function($v) { return $v['estado'] === 'pendiente'; })) ?>
                    </h2>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?= Helper::url('trabajador/valoraciones') ?>" class="text-dark">Ver detalles <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card bg-info text-white shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Servicios</h5>
                    <h2 class="display-4">
                        <?= count($servicios) ?>
                    </h2>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="<?= Helper::url('trabajador/servicios') ?>" class="text-white">Ver detalles <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Próximas Reservas</h5>
                        <a href="<?= Helper::url('trabajador/reservas') ?>" class="btn btn-sm btn-primary">Ver todas</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Servicio</th>
                                    <th>Trabajador</th>
                                    <th>Fecha y Hora</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Filtrar reservas pendientes y ordenar por fecha
                                $proximasReservas = array_filter($reservas, function($r) {
                                    return $r['estado'] === 'pendiente' && strtotime($r['fecha_hora']) > time();
                                });
                                usort($proximasReservas, function($a, $b) {
                                    return strtotime($a['fecha_hora']) - strtotime($b['fecha_hora']);
                                });
                                $proximasReservas = array_slice($proximasReservas, 0, 5);
                                ?>

                                <?php if (empty($proximasReservas)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No hay reservas próximas</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($proximasReservas as $reserva): ?>
                                        <tr>
                                            <td><?= Helper::e($reserva['nombre_usuario']) ?></td>
                                            <td><?= Helper::e($reserva['nombre_servicio']) ?></td>
                                            <td><?= Helper::e($reserva['nombre_trabajador']) ?></td>
                                            <td><?= Helper::formatDate($reserva['fecha_hora']) ?></td>
                                            <td>
                                                <span class="badge bg-warning text-dark">Pendiente</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Valoraciones Recientes</h5>
                        <a href="<?= Helper::url('trabajador/valoraciones') ?>" class="btn btn-sm btn-primary">Ver todas</a>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                    // Ordenar valoraciones por fecha y mostrar las 2 más recientes (en lugar de 5)
                    usort($valoraciones, function($a, $b) {
                        return strtotime($b['fecha_creacion']) - strtotime($a['fecha_creacion']);
                    });
                    $valoracionesRecientes = array_slice($valoraciones, 0, 2); // Cambiamos 5 por 2
                    ?>

                    <?php if (empty($valoracionesRecientes)): ?>
                        <p class="text-center">No hay valoraciones recientes</p>
                    <?php else: ?>
                        <?php foreach ($valoracionesRecientes as $valoracion): ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="card-subtitle mb-2 text-muted">
                                            <?= Helper::e($valoracion['nombre_usuario']) ?> - <?= Helper::e($valoracion['nombre_servicio']) ?>
                                        </h6>
                                        <span class="text-muted small"><?= Helper::formatDate($valoracion['fecha_creacion']) ?></span>
                                    </div>

                                    <div class="mb-2">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $valoracion['puntuacion']): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-warning"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>

                                    <p class="card-text"><?= Helper::e($valoracion['comentario']) ?></p>

                                    <?php if ($valoracion['estado'] == 'pendiente'): ?>
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    <?php elseif ($valoracion['estado'] == 'aprobada'): ?>
                                        <span class="badge bg-success">Aprobada</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Rechazada</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Información de Contacto</h5>
                </div>
                <div class="card-body">
                    <p>Si necesitas asistencia técnica o tienes alguna consulta, contacta con el administrador:</p>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-envelope me-2 text-primary"></i>
                                    <a href="mailto:admin@spaibaiondo.com">admin@spaibaiondo.com</a>
                                </li>
                                <li>
                                    <i class="fas fa-phone me-2 text-primary"></i>
                                    <a href="tel:+34946123456">+34 946 123 456</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>