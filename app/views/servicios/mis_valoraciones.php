<div class="container py-5">
    <h1 class="mb-4">Mis Valoraciones</h1>

    <?php if (empty($valoraciones)): ?>
        <div class="alert alert-info">
            <p class="mb-0">No has realizado ninguna valoración.</p>
        </div>
        <div class="text-center mt-4">
            <a href="<?= Helper::url('servicios') ?>" class="btn btn-primary">Ver Servicios</a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($valoraciones as $valoracion): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0"><?= Helper::e($valoracion['nombre_servicio']) ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= $valoracion['puntuacion']): ?>
                                            <i class="fas fa-star text-warning"></i>
                                        <?php else: ?>
                                            <i class="far fa-star text-warning"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </div>
                                <small class="text-muted"><?= Helper::formatDate($valoracion['fecha_creacion']) ?></small>
                            </div>
                            <p class="card-text"><?= nl2br(Helper::e($valoracion['comentario'])) ?></p>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="d-flex justify-content-between">
                                <a href="<?= Helper::url('servicios/' . $valoracion['id_servicio']) ?>" class="btn btn-sm btn-outline-primary">
                                    Ver Servicio
                                </a>
                                <div>
                                    <a href="<?= Helper::url('servicios/' . $valoracion['id_servicio'] . '/valorar') ?>" class="btn btn-sm btn-outline-secondary">
                                        Editar
                                    </a>
                                    <a href="<?= Helper::url('servicios/valoracion/' . $valoracion['id'] . '/eliminar') ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('¿Estás seguro de eliminar esta valoración?')">
                                        Eliminar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

