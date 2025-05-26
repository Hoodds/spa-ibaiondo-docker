<div class="container py-4">
    <h1 class="mb-4">Valoraciones de Mis Servicios</h1>

    <?php if (empty($valoraciones)): ?>
        <div class="alert alert-info">
            <p class="mb-0">No hay valoraciones para tus servicios.</p>
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
                            <p class="mb-1"><strong>Cliente:</strong> <?= Helper::e($valoracion['nombre_usuario']) ?></p>
                            <p class="card-text"><?= nl2br(Helper::e($valoracion['comentario'])) ?></p>
                            <div class="mt-3">
                                <span class="badge <?= $valoracion['estado'] == 'aprobada' ? 'bg-success' : ($valoracion['estado'] == 'pendiente' ? 'bg-warning text-dark' : 'bg-danger') ?>">
                                    <?= ucfirst($valoracion['estado']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

