<div class="container py-4">
    <h1 class="mb-4">Mis Reservas Asignadas</h1>

    <?php if (empty($reservas)): ?>
        <div class="alert alert-info">
            <p class="mb-0">No tienes reservas asignadas.</p>
        </div>
    <?php else: ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Servicio</th>
                                <th>Fecha y Hora</th>
                                <th>Duración</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservas as $reserva): ?>
                                <tr>
                                    <td><?= $reserva['id'] ?></td>
                                    <td><?= Helper::e($reserva['nombre_usuario']) ?></td>
                                    <td><?= Helper::e($reserva['nombre_servicio']) ?></td>
                                    <td><?= Helper::formatDate($reserva['fecha_hora']) ?></td>
                                    <td><?= $reserva['duracion'] ?> min</td>
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
                                        <?php if ($reserva['estado'] == 'pendiente'): ?>
                                            <a href="<?= Helper::url('trabajador/reservas/' . $reserva['id'] . '/completar') ?>" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            <a href="<?= Helper::url('trabajador/reservas/' . $reserva['id'] . '/cancelar') ?>"
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('¿Estás seguro de que deseas cancelar esta reserva?')">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-info toggle-collapse"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#verReserva<?= $reserva['id'] ?>"
                                                aria-expanded="false">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>

                                <tr class="collapse-row">
                                    <td colspan="7" class="p-0">
                                        <div class="collapse" id="verReserva<?= $reserva['id'] ?>">
                                            <div class="card card-body m-2">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>ID:</strong> <?= $reserva['id'] ?></p>
                                                        <p><strong>Cliente:</strong> <?= Helper::e($reserva['nombre_usuario']) ?></p>
                                                        <p><strong>Email Cliente:</strong> <?= Helper::e($reserva['email_usuario'] ?? 'No disponible') ?></p>
                                                        <p><strong>Servicio:</strong> <?= Helper::e($reserva['nombre_servicio']) ?></p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Fecha y Hora:</strong> <?= Helper::formatDate($reserva['fecha_hora']) ?></p>
                                                        <p><strong>Duración:</strong> <?= $reserva['duracion'] ?> minutos</p>
                                                        <p><strong>Precio:</strong> <?= Helper::formatPrice($reserva['precio']) ?></p>
                                                        <p><strong>Estado:</strong>
                                                            <?php if ($reserva['estado'] == 'pendiente'): ?>
                                                                <span class="badge bg-warning text-dark">Pendiente</span>
                                                            <?php elseif ($reserva['estado'] == 'confirmada'): ?>
                                                                <span class="badge bg-success">Confirmada</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-danger">Cancelada</span>
                                                            <?php endif; ?>
                                                        </p>
                                                    </div>
                                                </div>

                                                <?php if ($reserva['estado'] == 'pendiente'): ?>
                                                <div class="row mt-3">
                                                    <div class="col-12 text-end">
                                                        <a href="<?= Helper::url('trabajador/reservas/' . $reserva['id'] . '/completar') ?>"
                                                           class="btn btn-success">
                                                            <i class="fas fa-check"></i> Marcar como Confirmada
                                                        </a>
                                                        <a href="<?= Helper::url('trabajador/reservas/' . $reserva['id'] . '/cancelar') ?>"
                                                           class="btn btn-danger"
                                                           onclick="return confirm('¿Estás seguro de cancelar esta reserva?')">
                                                            <i class="fas fa-times"></i> Cancelar Reserva
                                                        </a>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    initAdminCollapses();
});
</script>

