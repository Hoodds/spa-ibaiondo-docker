<div class="container py-4">
    <h1 class="mb-4">Bienvenido, <?= Helper::e($_SESSION['trabajador_nombre']) ?></h1>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Mis Próximas Reservas</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($reservas)): ?>
                        <p class="text-center">No tienes reservas asignadas.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Servicio</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 0;
                                    foreach ($reservas as $reserva):
                                        if ($count >= 5) break;
                                        $count++;
                                    ?>
                                        <tr>
                                            <td><?= Helper::e($reserva['nombre_usuario']) ?></td>
                                            <td><?= Helper::e($reserva['nombre_servicio']) ?></td>
                                            <td><?= Helper::formatDate($reserva['fecha_hora']) ?></td>
                                            <td>
                                                <?php if ($reserva['estado'] == 'pendiente'): ?>
                                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                                <?php elseif ($reserva['estado'] == 'confirmada'): ?>
                                                    <span class="badge bg-success">Confirmada</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Cancelada</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if (count($reservas) > 5): ?>
                            <div class="text-center mt-3">
                                <a href="<?= Helper::url('trabajador/reservas') ?>" class="btn btn-outline-primary btn-sm">Ver todas</a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Valoraciones Recientes</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($valoraciones)): ?>
                        <p class="text-center">No hay valoraciones para tus servicios.</p>
                    <?php else: ?>
                        <div class="valoraciones-scroll" style="max-height: 300px; overflow-y: auto;">
                            <?php
                            $count = 0;
                            foreach ($valoraciones as $valoracion):
                                if ($count >= 5) break;
                                $count++;
                            ?>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="card-subtitle"><?= Helper::e($valoracion['nombre_usuario']) ?></h6>
                                            <small class="text-muted"><?= Helper::formatDate($valoracion['fecha_creacion']) ?></small>
                                        </div>
                                        <p class="mb-1"><strong><?= Helper::e($valoracion['nombre_servicio']) ?></strong></p>
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
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($valoraciones) > 5): ?>
                            <div class="text-center mt-3">
                                <a href="<?= Helper::url('trabajador/valoraciones') ?>" class="btn btn-outline-primary btn-sm">Ver todas</a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Información de Contacto</h5>
                </div>
                <div class="card-body">
                    <p>Si tienes alguna duda o necesitas asistencia, contacta con el administrador:</p>
                    <ul class="list-unstyled">
                        <li>
                            <i class="fas fa-envelope me-2"></i>
                            <a href="mailto:admin@spaibaiondo.com">admin@spaibaiondo.com</a>
                        </li>
                        <li>
                            <i class="fas fa-phone me-2"></i>
                            <a href="tel:+34946123456">+34 946 123 456</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

