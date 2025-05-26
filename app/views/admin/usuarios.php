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
        <h1>Gestión de Usuarios</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevoUsuarioModal">
            <i class="fas fa-plus"></i> Nuevo Usuario
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
                            <th>Fecha de Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($usuarios)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No hay usuarios registrados</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td><?= $usuario['id'] ?></td>
                                    <td><?= Helper::e($usuario['nombre']) ?></td>
                                    <td><?= Helper::e($usuario['email']) ?></td>
                                    <td><?= Helper::formatDate($usuario['fecha_registro'], 'd/m/Y') ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info toggle-collapse"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#verUsuario<?= $usuario['id'] ?>"
                                                aria-expanded="false">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning toggle-collapse"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#editarUsuario<?= $usuario['id'] ?>"
                                                aria-expanded="false">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="<?= Helper::url('/admin/usuarios/eliminar/' . $usuario['id']) ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- Collapse Ver Usuario -->
                                <tr class="collapse-row">
                                    <td colspan="5" class="p-0">
                                        <div class="collapse" id="verUsuario<?= $usuario['id'] ?>">
                                            <div class="card card-body m-2">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>ID:</strong> <?= $usuario['id'] ?></p>
                                                        <p><strong>Nombre:</strong> <?= Helper::e($usuario['nombre']) ?></p>
                                                        <p><strong>Email:</strong> <?= Helper::e($usuario['email']) ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Fecha de Registro:</strong> <?= Helper::formatDate($usuario['fecha_registro'], 'd/m/Y H:i') ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Collapse Editar Usuario -->
                                <tr class="collapse-row">
                                    <td colspan="5" class="p-0">
                                        <div class="collapse" id="editarUsuario<?= $usuario['id'] ?>">
                                            <div class="card card-body m-2">
                                                <form action="<?= Helper::url('/admin/usuarios/editar') ?>" method="POST" class="row g-3">
                                                    <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

                                                    <div class="col-md-6">
                                                        <label for="nombre<?= $usuario['id'] ?>" class="form-label">Nombre</label>
                                                        <input type="text" class="form-control" id="nombre<?= $usuario['id'] ?>" name="nombre" value="<?= Helper::e($usuario['nombre']) ?>" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="email<?= $usuario['id'] ?>" class="form-label">Email</label>
                                                        <input type="email" class="form-control" id="email<?= $usuario['id'] ?>" name="email" value="<?= Helper::e($usuario['email']) ?>" required>
                                                    </div>

                                                    <div class="col-12">
                                                        <label for="password<?= $usuario['id'] ?>" class="form-label">Nueva Contraseña</label>
                                                        <input type="password" class="form-control" id="password<?= $usuario['id'] ?>" name="password">
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

<!-- Modal Nuevo Usuario -->
<div class="modal fade" id="nuevoUsuarioModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= Helper::url('/admin/usuarios/crear') ?>" method="POST">
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
                        <label for="nuevoPassword" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="nuevoPassword" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

