<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestión de Servicios</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoServicioModal">
            <i class="fas fa-plus"></i> Nuevo Servicio
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Duración</th>
                            <th>Precio</th>
                            <th>Valoración</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($servicios)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No hay servicios registrados</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($servicios as $servicio): ?>
                                <tr>
                                    <td><?= $servicio['id'] ?></td>
                                    <td><?= Helper::e($servicio['nombre']) ?></td>
                                    <td><?= $servicio['duracion'] ?> min.</td>
                                    <td><?= Helper::formatPrice($servicio['precio']) ?></td>
                                    <td>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= round($servicio['puntuacion_media'])): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-warning"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        (<?= $servicio['total_valoraciones'] ?>)
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info toggle-collapse"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#verServicio<?= $servicio['id'] ?>"
                                                aria-expanded="false">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning toggle-collapse"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#editarServicio<?= $servicio['id'] ?>"
                                                aria-expanded="false">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="<?= Helper::url('/admin/servicios/eliminar/' . $servicio['id']) ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('¿Estás seguro de eliminar este servicio?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- Collapse Ver Servicio -->
                                <tr class="collapse-row">
                                    <td colspan="6" class="p-0">
                                        <div class="collapse" id="verServicio<?= $servicio['id'] ?>">
                                            <div class="card card-body m-2">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>ID:</strong> <?= $servicio['id'] ?></p>
                                                        <p><strong>Nombre:</strong> <?= Helper::e($servicio['nombre']) ?></p>
                                                        <p><strong>Duración:</strong> <?= $servicio['duracion'] ?> minutos</p>
                                                        <p><strong>Precio:</strong> <?= Helper::formatPrice($servicio['precio']) ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Valoración:</strong></p>
                                                        <p>
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <?php if ($i <= round($servicio['puntuacion_media'])): ?>
                                                                    <i class="fas fa-star text-warning"></i>
                                                                <?php else: ?>
                                                                    <i class="far fa-star text-warning"></i>
                                                                <?php endif; ?>
                                                            <?php endfor; ?>
                                                            (<?= $servicio['puntuacion_media'] ?>/5 - <?= $servicio['total_valoraciones'] ?> valoraciones)
                                                        </p>
                                                        <p><strong>Descripción:</strong></p>
                                                        <p><?= nl2br(Helper::e($servicio['descripcion'])) ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Collapse Editar Servicio -->
                                <tr class="collapse-row">
                                    <td colspan="6" class="p-0">
                                        <div class="collapse" id="editarServicio<?= $servicio['id'] ?>">
                                            <div class="card card-body m-2">
                                                <form action="<?= Helper::url('/admin/servicios/editar') ?>" method="POST" class="row g-3">
                                                    <input type="hidden" name="id" value="<?= $servicio['id'] ?>">

                                                    <div class="col-md-6">
                                                        <label for="nombre<?= $servicio['id'] ?>" class="form-label">Nombre</label>
                                                        <input type="text" class="form-control" id="nombre<?= $servicio['id'] ?>" name="nombre" value="<?= Helper::e($servicio['nombre']) ?>" required>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="duracion<?= $servicio['id'] ?>" class="form-label">Duración (minutos)</label>
                                                        <input type="number" class="form-control" id="duracion<?= $servicio['id'] ?>" name="duracion" value="<?= $servicio['duracion'] ?>" required>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="precio<?= $servicio['id'] ?>" class="form-label">Precio (€)</label>
                                                        <input type="number" step="0.01" class="form-control" id="precio<?= $servicio['id'] ?>" name="precio" value="<?= $servicio['precio'] ?>" required>
                                                    </div>

                                                    <div class="col-12">
                                                        <label for="descripcion<?= $servicio['id'] ?>" class="form-label">Descripción</label>
                                                        <textarea class="form-control" id="descripcion<?= $servicio['id'] ?>" name="descripcion" rows="3" required><?= Helper::e($servicio['descripcion']) ?></textarea>
                                                    </div>

                                                    <div class="col-12 text-end mt-3">
                                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                    </div>
                                                </form>
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

<!-- Modal Nuevo Servicio -->
<div class="modal fade" id="nuevoServicioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= Helper::url('/admin/servicios/crear') ?>" method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="duracion" class="form-label">Duración (minutos)</label>
                        <input type="number" class="form-control" id="duracion" name="duracion" required>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio (€)</label>
                        <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Crear Servicio</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

