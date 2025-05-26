<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <img src="<?= Helper::asset('images/servicios/' . ($servicio['imagen'] ?? 'servicio-default.jpg')) ?>"
                     alt="<?= Helper::e($servicio['nombre']) ?>"
                     class="card-img-top img-detalle-servicio"
                     onerror="this.src='<?= Helper::asset('images/servicio-default.jpg') ?>'">
                <div class="card-body">
                    <h1 class="card-title"><?= Helper::e($servicio['nombre']) ?></h1>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <span class="badge bg-primary me-2">
                                <i class="far fa-clock"></i> <?= $servicio['duracion'] ?> minutos
                            </span>
                            <span class="badge bg-info">
                                <i class="fas fa-tag"></i> <?= Helper::formatPrice($servicio['precio']) ?>
                            </span>
                        </div>
                        <?php if (Auth::check()): ?>
                            <a href="<?= Helper::url('reservas/crear/' . $servicio['id']) ?>" class="btn btn-primary">
                                <i class="fas fa-calendar-plus"></i> Reservar Ahora
                            </a>
                        <?php else: ?>
                            <a href="<?= Helper::url('login') ?>" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Iniciar Sesión para Reservar
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- Puntuación media -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <span class="h5 mb-0"><?= $servicio['puntuacion_media'] ?></span>
                                <span class="text-muted"> / 5</span>
                            </div>
                            <div class="me-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= round($servicio['puntuacion_media'])): ?>
                                        <i class="fas fa-star text-warning"></i>
                                    <?php else: ?>
                                        <i class="far fa-star text-warning"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <div>
                                <span class="text-muted">(<?= $servicio['total_valoraciones'] ?> valoraciones)</span>
                            </div>
                        </div>
                    </div>

                    <h5 class="card-subtitle mb-3">Descripción</h5>
                    <p class="card-text"><?= nl2br(Helper::e($servicio['descripcion'])) ?></p>

                    <h5 class="card-subtitle mb-3 mt-4">Beneficios</h5>
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Reduce el estrés y la ansiedad</li>
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Mejora la circulación sanguínea</li>
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Alivia dolores musculares</li>
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Promueve la relajación profunda</li>
                        <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Mejora la calidad del sueño</li>
                    </ul>

                    <div class="d-flex justify-content-between">
                        <a href="<?= Helper::url('servicios') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Volver a Servicios
                        </a>
                        <?php if (Auth::check()): ?>
                            <a href="<?= Helper::url('reservas/crear/' . $servicio['id']) ?>" class="btn btn-primary">
                                <i class="fas fa-calendar-plus"></i> Reservar Ahora
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sección de valoraciones -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h4 class="card-title mb-0">Valoraciones</h4>
                </div>
                <div class="card-body">
                    <?php if (Auth::check()): ?>
                        <?php if (!$usuarioHaValorado): ?>
                            <!-- Formulario para añadir valoración -->
                            <form action="<?= Helper::url('servicios/' . $servicio['id'] . '/valorar') ?>" method="post" class="mb-4">
                                <h5>Deja tu valoración</h5>
                                <div class="mb-3">
                                    <label class="form-label">Puntuación</label>
                                    <div class="star-rating">
                                    <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="puntuacion" id="star1" value="1" required>
                                            <label class="form-check-label" for="star1"><i class="far fa-star"></i></label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="puntuacion" id="star2" value="2">
                                            <label class="form-check-label" for="star2"><i class="far fa-star"></i></label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="puntuacion" id="star3" value="3">
                                            <label class="form-check-label" for="star3"><i class="far fa-star"></i></label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="puntuacion" id="star4" value="4">
                                            <label class="form-check-label" for="star4"><i class="far fa-star"></i></label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="puntuacion" id="star5" value="5">
                                            <label class="form-check-label" for="star5"><i class="far fa-star"></i></label>
                                        </div>

                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="comentario" class="form-label">Comentario</label>
                                    <textarea class="form-control" id="comentario" name="comentario" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Enviar Valoración</button>
                            </form>
                        <?php else: ?>
                            <!-- Mostrar la valoración del usuario -->
                            <div class="alert alert-info mb-4">
                                <h5>Tu valoración</h5>
                                <div class="d-flex align-items-center mb-2">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?php if ($i <= $valoracionUsuario['puntuacion']): ?>
                                            <i class="fas fa-star text-warning me-1"></i>
                                        <?php else: ?>
                                            <i class="far fa-star text-warning me-1"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    <span class="ms-2"><?= Helper::formatDate($valoracionUsuario['fecha_creacion']) ?></span>
                                </div>
                                <p class="mb-0"><?= nl2br(Helper::e($valoracionUsuario['comentario'])) ?></p>
                                <div class="mt-2">
                                    <a href="<?= Helper::url('servicios/valoracion/' . $valoracionUsuario['id'] . '/eliminar') ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('¿Estás seguro de eliminar tu valoración?')">
                                        Eliminar
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-info mb-4">
                            <p class="mb-0">
                                <a href="<?= Helper::url('login') ?>">Inicia sesión</a> para dejar tu valoración.
                            </p>
                        </div>
                    <?php endif; ?>

                    <!-- Listado de valoraciones -->
                    <?php if (empty($valoraciones)): ?>
                        <p class="text-center">No hay valoraciones para este servicio.</p>
                    <?php else: ?>
                        <h5>Opiniones de otros usuarios</h5>
                        <?php foreach ($valoraciones as $valoracion): ?>
                            <?php if (Auth::check() && $valoracion['id_usuario'] == Auth::id()) continue; ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="card-subtitle"><?= Helper::e($valoracion['nombre_usuario']) ?></h6>
                                        <small class="text-muted"><?= Helper::formatDate($valoracion['fecha_creacion']) ?></small>
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
                                    <p class="card-text"><?= nl2br(Helper::e($valoracion['comentario'])) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Script para la funcionalidad de las estrellas en el formulario de valoración
document.addEventListener('DOMContentLoaded', function() {
    const starLabels = document.querySelectorAll('.star-rating .form-check-label');
    const starInputs = document.querySelectorAll('.star-rating input[type="radio"]');

    // Función para actualizar las estrellas
    function updateStars(rating) {
        starLabels.forEach((label, index) => {
            if (index < rating) {
                label.innerHTML = '<i class="fas fa-star text-warning"></i>'; // Estrella llena
            } else {
                label.innerHTML = '<i class="far fa-star text-warning"></i>'; // Estrella vacía
            }
        });
    }

    // Evento para cuando se selecciona una estrella
    starInputs.forEach((input) => {
        input.addEventListener('change', function() {
            updateStars(parseInt(this.value)); // Actualizar las estrellas según el valor seleccionado
        });
    });

    // Evento para cuando se pasa el ratón por encima
    starLabels.forEach((label, index) => {
        label.addEventListener('mouseenter', function() {
            updateStars(index + 1); // Actualizar las estrellas al pasar el ratón
        });

        label.addEventListener('mouseleave', function() {
            const selectedRating = document.querySelector('.star-rating input[type="radio"]:checked');
            if (selectedRating) {
                updateStars(parseInt(selectedRating.value)); // Restaurar las estrellas según la selección actual
            } else {
                updateStars(0); // Si no hay selección, mostrar todas las estrellas vacías
            }
        });
    });

    // Inicializar las estrellas según la selección actual al cargar la página
    const selectedRating = document.querySelector('.star-rating input[type="radio"]:checked');
    if (selectedRating) {
        updateStars(parseInt(selectedRating.value));
    }
});
</script>

