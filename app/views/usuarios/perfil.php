<div class="container py-5">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <!-- <img src="<?= Helper::asset('images/avatar-default.jpg') ?>" alt="Foto de perfil" class="rounded-circle img-thumbnail" onerror="this.src='https://via.placeholder.com/150'"> -->
                    </div>
                    <h4><?= Helper::e($usuario['nombre']) ?></h4>
                    <p class="text-muted"><?= Helper::e($usuario['email']) ?></p>
                    <p><small>Miembro desde: <?= Helper::formatDate($usuario['fecha_registro'], 'd/m/Y') ?></small></p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Mis Reservas</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    <?php if (empty($reservas)): ?>
                        <p class="text-center">No tienes reservas activas.</p>
                        <div class="text-center">
                            <a href="<?= Helper::url('servicios') ?>" class="btn btn-primary">Reservar Ahora</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Servicio</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reservas as $reserva): ?>
                                        <tr>
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
                                            <td>
                                                <?php if ($reserva['estado'] != 'cancelada'): ?>
                                                    <a href="<?= Helper::url('reservas/' . $reserva['id'] . '/cancelar') ?>"
                                                       class="btn btn-sm btn-outline-danger"
                                                       onclick="return confirm('¿Estás seguro de cancelar esta reserva?')">
                                                        Cancelar
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end mt-3">
                            <a href="<?= Helper::url('reservas') ?>" class="btn btn-primary">Ver Todas</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Editar Perfil</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    <form method="POST" action="<?= Helper::url('perfil') ?>">
                        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= Helper::e($usuario['nombre']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= Helper::e($usuario['email']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="form-text">Dejar en blanco para mantener la contraseña actual.</div>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirm" class="form-label">Confirmar Nueva Contraseña</label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>