<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestión de Trabajadores</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoTrabajadorModal">
            <i class="fas fa-plus"></i> Nuevo Trabajador
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
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($trabajadores)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No hay trabajadores registrados</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($trabajadores as $trabajador): ?>
                                <?php
                                // Determinar la clase de la etiqueta de rol
                                switch($trabajador['rol']) {
                                    case 'admin':
                                        $badgeClass = 'bg-danger';
                                        break;
                                    case 'recepcionista':
                                        $badgeClass = 'bg-primary';
                                        break;
                                    case 'masajista':
                                        $badgeClass = 'bg-success';
                                        break;
                                    case 'terapeuta':
                                        $badgeClass = 'bg-info';
                                        break;
                                    default:
                                        $badgeClass = 'bg-warning';
                                        break;
                                }
                                ?>
                                <tr>
                                    <td><?= $trabajador['id'] ?></td>
                                    <td><?= Helper::e($trabajador['nombre']) ?></td>
                                    <td><?= Helper::e($trabajador['email']) ?></td>
                                    <td>
                                        <span class="badge <?= $badgeClass ?>"><?= ucfirst($trabajador['rol']) ?></span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info toggle-collapse"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#verTrabajador<?= $trabajador['id'] ?>"
                                                aria-expanded="false">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning toggle-collapse"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#editarTrabajador<?= $trabajador['id'] ?>"
                                                aria-expanded="false">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="<?= Helper::url('/admin/trabajadores/eliminar/' . $trabajador['id']) ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('¿Estás seguro de eliminar este trabajador?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- Collapse Ver Trabajador -->
                                <tr class="collapse-row">
                                    <td colspan="5" class="p-0">
                                        <div class="collapse" id="verTrabajador<?= $trabajador['id'] ?>">
                                            <div class="card card-body m-2">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>ID:</strong> <?= $trabajador['id'] ?></p>
                                                        <p><strong>Nombre:</strong> <?= Helper::e($trabajador['nombre']) ?></p>
                                                        <p><strong>Email:</strong> <?= Helper::e($trabajador['email']) ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Rol:</strong> <span class="badge <?= $badgeClass ?>"><?= ucfirst($trabajador['rol']) ?></span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Collapse Editar Trabajador -->
                                <tr class="collapse-row">
                                    <td colspan="5" class="p-0">
                                        <div class="collapse" id="editarTrabajador<?= $trabajador['id'] ?>">
                                            <div class="card card-body m-2">
                                                <form action="<?= Helper::url('/admin/trabajadores/editar') ?>" method="POST" class="row g-3">
                                                    <input type="hidden" name="id" value="<?= $trabajador['id'] ?>">

                                                    <div class="col-md-6">
                                                        <label for="nombre<?= $trabajador['id'] ?>" class="form-label">Nombre</label>
                                                        <input type="text" class="form-control" id="nombre<?= $trabajador['id'] ?>" name="nombre" value="<?= Helper::e($trabajador['nombre']) ?>" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="email<?= $trabajador['id'] ?>" class="form-label">Email</label>
                                                        <input type="email" class="form-control" id="email<?= $trabajador['id'] ?>" name="email" value="<?= Helper::e($trabajador['email']) ?>" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="rol<?= $trabajador['id'] ?>" class="form-label">Rol</label>
                                                        <select class="form-select" id="rol<?= $trabajador['id'] ?>" name="rol" required>
                                                            <option value="admin" <?= $trabajador['rol'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
                                                            <option value="recepcionista" <?= $trabajador['rol'] == 'recepcionista' ? 'selected' : '' ?>>Recepcionista</option>
                                                            <option value="masajista" <?= $trabajador['rol'] == 'masajista' ? 'selected' : '' ?>>Masajista</option>
                                                            <option value="terapeuta" <?= $trabajador['rol'] == 'terapeuta' ? 'selected' : '' ?>>Terapeuta</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="password<?= $trabajador['id'] ?>" class="form-label">Nueva Contraseña</label>
                                                        <input type="password" class="form-control" id="password<?= $trabajador['id'] ?>" name="password">
                                                        <div class="form-text">Dejar en blanco para mantener la contraseña actual.</div>
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

<!-- Modal Nuevo Trabajador -->
<div class="modal fade" id="nuevoTrabajadorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Trabajador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= Helper::url('/admin/trabajadores/crear') ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nuevoNombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nuevoNombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="nuevoEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="nuevoEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="nuevoRol" class="form-label">Rol</label>
                        <select class="form-select" id="nuevoRol" name="rol" required>
                            <option value="admin">Administrador</option>
                            <option value="recepcionista">Recepcionista</option>
                            <option value="masajista">Masajista</option>
                            <option value="terapeuta">Terapeuta</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nuevoPassword" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="nuevoPassword" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Trabajador</button>
                </div>
            </form>
        </div>
    </div>
</div>

