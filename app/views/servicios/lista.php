<div class="container py-5">
    <h1 class="text-center mb-5">Nuestros Servicios</h1>

    <div class="row">
        <?php foreach ($servicios as $servicio): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?= Helper::asset('images/servicios/' . ($servicio['imagen'] ?? 'servicio-default.jpg')) ?>"
                         alt="<?= Helper::e($servicio['nombre']) ?>"
                         class="card-img-top"
                         onerror="if(this.src.indexOf('servicio-default.jpg') === -1) this.src='<?= Helper::asset('images/servicios/servicio-default.jpg') ?>';">
                    <div class="card-body">
                        <h5 class="card-title"><?= Helper::e($servicio['nombre']) ?></h5>

                        <div class="mb-2">
                            <div class="d-flex align-items-center">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= round($servicio['puntuacion_media'])): ?>
                                        <i class="fas fa-star text-warning"></i>
                                    <?php else: ?>
                                        <i class="far fa-star text-warning"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                <span class="ms-2 text-muted">
                                    (<?= $servicio['total_valoraciones'] ?> valoraciones)
                                </span>
                            </div>
                        </div>

                        <p class="card-text"><?= substr(Helper::e($servicio['descripcion']), 0, 100) ?>...</p>
                        <p class="card-text">
                            <small class="text-muted">
                                <i class="far fa-clock"></i> <?= $servicio['duracion'] ?> minutos |
                                <strong><?= Helper::formatPrice($servicio['precio']) ?></strong>
                            </small>
                        </p>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <div class="d-flex justify-content-between">
                            <a href="<?= Helper::url('servicios/' . $servicio['id']) ?>" class="btn btn-outline-primary">Ver Detalles</a>
                            <?php if (Auth::check()): ?>
                                <a href="<?= Helper::url('reservas/crear/' . $servicio['id']) ?>" class="btn btn-primary">Reservar</a>
                            <?php else: ?>
                                <a href="<?= Helper::url('login') ?>" class="btn btn-primary">Iniciar Sesión para Reservar</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

