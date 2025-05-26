<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Valoraciones Pendientes</h1>
        <a href="<?= Helper::url('admin/valoraciones') ?>" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Volver a todas las valoraciones
        </a>
    </div>

    <?php if (empty($valoraciones)): ?>
        <div class="alert alert-info">No hay valoraciones pendientes de aprobación.</div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Servicio</th>
                                <th>Puntuación</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($valoraciones as $valoracion): ?>
                                <tr>
                                    <td><?= $valoracion['id'] ?></td>
                                    <td><?= Helper::e($valoracion['nombre_usuario']) ?></td>
                                    <td><?= Helper::e($valoracion['nombre_servicio']) ?></td>
                                    <td>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $valoracion['puntuacion']): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-warning"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </td>
                                    <td><?= Helper::formatDate($valoracion['fecha_creacion'], 'd/m/Y') ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info toggle-collapse"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#verValoracion<?= $valoracion['id'] ?>"
                                                aria-expanded="false">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="<?= Helper::url('admin/valoraciones/' . $valoracion['id'] . '/aprobar') ?>" class="btn btn-sm btn-success" title="Aprobar">
                                            <i class="fas fa-check"></i> Aprobar
                                        </a>
                                        <a href="<?= Helper::url('admin/valoraciones/' . $valoracion['id'] . '/rechazar') ?>" class="btn btn-sm btn-danger" title="Rechazar">
                                            <i class="fas fa-times"></i> Rechazar
                                        </a>
                                    </td>
                                </tr>

                                <tr class="collapse-row">
                                    <td colspan="6" class="p-0">
                                        <div class="collapse" id="verValoracion<?= $valoracion['id'] ?>">
                                            <div class="card card-body m-2">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>ID:</strong> <?= $valoracion['id'] ?></p>
                                                        <p><strong>Usuario:</strong> <?= Helper::e($valoracion['nombre_usuario']) ?></p>
                                                        <p><strong>Servicio:</strong> <?= Helper::e($valoracion['nombre_servicio']) ?></p>
                                                        <p><strong>Puntuación:</strong>
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <?php if ($i <= $valoracion['puntuacion']): ?>
                                                                    <i class="fas fa-star text-warning"></i>
                                                                <?php else: ?>
                                                                    <i class="far fa-star text-warning"></i>
                                                                <?php endif; ?>
                                                            <?php endfor; ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Fecha:</strong> <?= Helper::formatDate($valoracion['fecha_creacion'], 'd/m/Y H:i') ?></p>
                                                        <p><strong>Comentario:</strong></p>
                                                        <p><?= nl2br(Helper::e($valoracion['comentario'])) ?></p>
                                                    </div>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-12 text-end">
                                                        <a href="<?= Helper::url('admin/valoraciones/' . $valoracion['id'] . '/aprobar') ?>" class="btn btn-success">
                                                            <i class="fas fa-check"></i> Aprobar
                                                        </a>
                                                        <a href="<?= Helper::url('admin/valoraciones/' . $valoracion['id'] . '/rechazar') ?>" class="btn btn-danger">
                                                            <i class="fas fa-times"></i> Rechazar
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

