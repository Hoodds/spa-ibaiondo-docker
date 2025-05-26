<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Catálogo de Servicios</h1>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Duración</th>
                            <th>Precio</th>
                            <th>Valoración</th>
                            <th>Nº Valoraciones</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($servicios)): ?>
                            <tr>
                                <td colspan="8" class="text-center">No hay servicios registrados</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($servicios as $servicio): ?>
                                <tr>
                                    <td><?= $servicio['id'] ?></td>
                                    <td><?= Helper::e($servicio['nombre']) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-link toggle-collapse"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#desc<?= $servicio['id'] ?>"
                                                aria-expanded="false">
                                            Ver descripción
                                        </button>
                                    </td>
                                    <td><?= $servicio['duracion'] ?> min</td>
                                    <td><?= Helper::formatPrice($servicio['precio']) ?></td>
                                    <td>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $servicio['puntuacion_media']): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php elseif ($i - 0.5 <= $servicio['puntuacion_media']): ?>
                                                <i class="fas fa-star-half-alt text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-warning"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        (<?= $servicio['puntuacion_media'] ?>)
                                    </td>
                                    <td><?= $servicio['total_valoraciones'] ?></td>
                                    <td>
                                        <a href="<?= Helper::url('servicios/' . $servicio['id']) ?>" class="btn btn-sm btn-info" target="_blank">
                                            <i class="fas fa-eye"></i> Ver público
                                        </a>
                                    </td>
                                </tr>
                                <tr class="collapse-row">
                                    <td colspan="8" class="p-0">
                                        <div class="collapse" id="desc<?= $servicio['id'] ?>">
                                            <div class="card card-body m-2">
                                                <h6>Descripción completa:</h6>
                                                <p class="mb-0"><?= nl2br(Helper::e($servicio['descripcion'])) ?></p>
                                            </div>
                                        </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize collapsible elements
    const toggleButtons = document.querySelectorAll('.toggle-collapse');

    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Close all other open collapses
            const target = this.getAttribute('data-bs-target');
            document.querySelectorAll('.collapse.show').forEach(collapse => {
                if ('#' + collapse.id !== target) {
                    collapse.classList.remove('show');
                }
            });
        });
    });
});
</script>