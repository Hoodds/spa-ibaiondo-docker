<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestión de Reservas</h1>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nuevaReservaModal">
            <i class="fas fa-plus"></i> Nueva Reserva
        </button>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Filtros</h5>
        </div>
        <div class="card-body">
            <form class="row g-3" method="GET" action="<?= Helper::url('trabajador/reservas') ?>">
                <div class="col-md-3">
                    <label for="filtroFecha" class="form-label">Fecha</label>
                    <input type="date" class="form-control" id="filtroFecha" name="filtroFecha"
                           value="<?= $_GET['filtroFecha'] ?? '' ?>">
                </div>
                <div class="col-md-3">
                    <label for="filtroServicio" class="form-label">Servicio</label>
                    <select class="form-select" id="filtroServicio" name="filtroServicio">
                        <option value="">Todos los servicios</option>
                        <?php foreach ($servicios as $servicio): ?>
                            <option value="<?= $servicio['id'] ?>" <?= (isset($_GET['filtroServicio']) && $_GET['filtroServicio'] == $servicio['id']) ? 'selected' : '' ?>>
                                <?= Helper::e($servicio['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtroTrabajador" class="form-label">Trabajador</label>
                    <select class="form-select" id="filtroTrabajador" name="filtroTrabajador">
                        <option value="">Todos los trabajadores</option>
                        <?php foreach ($trabajadores as $trabajador): ?>
                            <?php if ($trabajador['rol'] !== 'admin' && $trabajador['rol'] !== 'recepcionista'): ?>
                                <option value="<?= $trabajador['id'] ?>" <?= (isset($_GET['filtroTrabajador']) && $_GET['filtroTrabajador'] == $trabajador['id']) ? 'selected' : '' ?>>
                                    <?= Helper::e($trabajador['nombre']) ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtroEstado" class="form-label">Estado</label>
                    <select class="form-select" id="filtroEstado" name="filtroEstado">
                        <option value="">Todos los estados</option>
                        <option value="pendiente" <?= (isset($_GET['filtroEstado']) && $_GET['filtroEstado'] == 'pendiente') ? 'selected' : '' ?>>Pendiente</option>
                        <option value="confirmada" <?= (isset($_GET['filtroEstado']) && $_GET['filtroEstado'] == 'confirmada') ? 'selected' : '' ?>>Confirmada</option>
                        <option value="cancelada" <?= (isset($_GET['filtroEstado']) && $_GET['filtroEstado'] == 'cancelada') ? 'selected' : '' ?>>Cancelada</option>
                    </select>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <a href="<?= Helper::url('trabajador/reservas') ?>" class="btn btn-outline-secondary">Limpiar filtros</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Servicio</th>
                            <th>Trabajador</th>
                            <th>Fecha y Hora</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reservas)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No hay reservas registradas</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reservas as $reserva): ?>
                                <tr>
                                    <td><?= $reserva['id'] ?></td>
                                    <td><?= Helper::e($reserva['nombre_usuario']) ?></td>
                                    <td><?= Helper::e($reserva['nombre_servicio']) ?></td>
                                    <td><?= Helper::e($reserva['nombre_trabajador']) ?></td>
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
                                        <button type="button" class="btn btn-sm btn-info toggle-collapse"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#verReserva<?= $reserva['id'] ?>"
                                                aria-expanded="false">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning toggle-collapse"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#editarReserva<?= $reserva['id'] ?>"
                                                aria-expanded="false">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <?php if ($reserva['estado'] == 'pendiente'): ?>
                                            <a href="<?= Helper::url('trabajador/reservas/' . $reserva['id'] . '/completar') ?>"
                                               class="btn btn-sm btn-success"
                                               onclick="return confirm('¿Confirmar esta reserva?')">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            <a href="<?= Helper::url('trabajador/reservas/' . $reserva['id'] . '/cancelar') ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('¿Estás seguro de cancelar esta reserva?')">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        <?php endif; ?>
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
                                                        <p><strong>Trabajador:</strong> <?= Helper::e($reserva['nombre_trabajador']) ?></p>
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
                                                        <p><strong>Fecha de creación:</strong> <?= isset($reserva['fecha_creacion']) ? Helper::formatDate($reserva['fecha_creacion']) : 'No disponible' ?></p>
                                                    </div>
                                                </div>

                                                <?php if ($reserva['estado'] == 'pendiente'): ?>
                                                <div class="row mt-3">
                                                    <div class="col-12 text-end">
                                                        <a href="<?= Helper::url('trabajador/reservas/' . $reserva['id'] . '/completar') ?>"
                                                           class="btn btn-success"
                                                           onclick="return confirm('¿Confirmar esta reserva?')">
                                                            <i class="fas fa-check"></i> Confirmar Reserva
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

                                <tr class="collapse-row">
                                    <td colspan="7" class="p-0">
                                        <div class="collapse" id="editarReserva<?= $reserva['id'] ?>">
                                            <div class="card card-body m-2">
                                                <form action="<?= Helper::url('/trabajador/reservas/editar') ?>" method="POST" class="row g-3">
                                                    <input type="hidden" name="id" value="<?= $reserva['id'] ?>">
                                                    <input type="hidden" name="id_servicio" value="<?= $reserva['id_servicio'] ?>">
                                                    <input type="hidden" name="id_usuario" value="<?= $reserva['id_usuario'] ?>">

                                                    <div class="col-md-4">
                                                        <label for="estado<?= $reserva['id'] ?>" class="form-label">Estado</label>
                                                        <select class="form-select" id="estado<?= $reserva['id'] ?>" name="estado" required>
                                                            <option value="pendiente" <?= $reserva['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                                            <option value="confirmada" <?= $reserva['estado'] == 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                                                            <option value="cancelada" <?= $reserva['estado'] == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="fecha<?= $reserva['id'] ?>" class="form-label">Fecha</label>
                                                        <?php $fecha = new DateTime($reserva['fecha_hora']); ?>
                                                        <input type="date" class="form-control fecha-reserva"
                                                               id="fecha<?= $reserva['id'] ?>"
                                                               name="fecha"
                                                               value="<?= $fecha->format('Y-m-d') ?>"
                                                               min="<?= date('Y-m-d') ?>"
                                                               data-reserva-id="<?= $reserva['id'] ?>"
                                                               data-servicio-id="<?= $reserva['id_servicio'] ?>"
                                                               required>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="hora<?= $reserva['id'] ?>" class="form-label">Hora</label>
                                                        <select class="form-select hora-reserva"
                                                                id="hora<?= $reserva['id'] ?>"
                                                                name="hora"
                                                                data-hora-actual="<?= $fecha->format('H:i') ?>"
                                                                required>
                                                            <option value="<?= $fecha->format('H:i') ?>"><?= $fecha->format('H:i') ?></option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label for="trabajador<?= $reserva['id'] ?>" class="form-label">Trabajador</label>
                                                        <select class="form-select trabajador-reserva"
                                                                id="trabajador<?= $reserva['id'] ?>"
                                                                name="id_trabajador"
                                                                data-trabajador-actual="<?= $reserva['id_trabajador'] ?>"
                                                                required>
                                                            <option value="<?= $reserva['id_trabajador'] ?>"><?= Helper::e($reserva['nombre_trabajador']) ?></option>
                                                        </select>
                                                        <div class="form-text">Selecciona una fecha para ver los trabajadores disponibles</div>
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

<div class="modal fade" id="nuevaReservaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Reserva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevaReserva" action="<?= Helper::url('/trabajador/reservas/crear') ?>" method="POST">
                    <div class="mb-3">
                        <label for="nuevoUsuario" class="form-label">Cliente</label>
                        <select class="form-select" id="nuevoUsuario" name="id_usuario" required>
                            <option value="">Seleccionar cliente</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?= $usuario['id'] ?>"><?= Helper::e($usuario['nombre']) ?> (<?= Helper::e($usuario['email']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nuevoServicio" class="form-label">Servicio</label>
                        <select class="form-select" id="nuevoServicio" name="id_servicio" required>
                            <option value="">Seleccionar servicio</option>
                            <?php foreach ($servicios as $servicio): ?>
                                <option value="<?= $servicio['id'] ?>"><?= Helper::e($servicio['nombre']) ?> - <?= Helper::formatPrice($servicio['precio']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nuevaFecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="nuevaFecha" name="fecha" required>
                    </div>
                    <div class="mb-3">
                        <label for="nuevoTrabajador" class="form-label">Trabajador</label>
                        <select class="form-select" id="nuevoTrabajador" name="id_trabajador" required>
                            <option value="">Seleccionar trabajador</option>
                            <?php foreach ($trabajadores as $trabajador): ?>
                                <?php if ($trabajador['rol'] !== 'admin' && $trabajador['rol'] !== 'recepcionista'): ?>
                                    <option value="<?= $trabajador['id'] ?>"><?= Helper::e($trabajador['nombre']) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nuevaHora" class="form-label">Hora</label>
                        <select class="form-select" id="nuevaHora" name="hora" required disabled>
                            <option value="">Seleccione fecha y trabajador primero</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nuevoEstado" class="form-label">Estado</label>
                        <select class="form-select" id="nuevoEstado" name="estado" required>
                            <option value="pendiente">Pendiente</option>
                            <option value="confirmada">Confirmada</option>
                            <option value="cancelada">Cancelada</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formNuevaReserva" class="btn btn-primary">Crear Reserva</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initAdminCollapses();

    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('nuevaFecha').min = hoy;

    const formNuevaReserva = document.getElementById('formNuevaReserva');
    if (formNuevaReserva) {
        formNuevaReserva.addEventListener('submit', function(e) {
            const servicio = document.getElementById('nuevoServicio').value;
            const usuario = document.getElementById('nuevoUsuario').value;
            const trabajador = document.getElementById('nuevoTrabajador').value;
            const fecha = document.getElementById('nuevaFecha').value;
            const hora = document.getElementById('nuevaHora').value;

            if (!servicio || !usuario || !trabajador || !fecha || !hora) {
                e.preventDefault();
                alert('Por favor, completa todos los campos requeridos.');
            }
        });
    }
});
</script>