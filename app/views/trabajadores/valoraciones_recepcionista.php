<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Valoraciones</h1>
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
                            <th>Puntuación</th>
                            <th>Comentario</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($valoraciones)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No hay valoraciones registradas</td>
                            </tr>
                        <?php else: ?>
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
                                    <td><?= Helper::e($valoracion['comentario']) ?></td>
                                    <td><?= Helper::formatDate($valoracion['fecha_creacion']) ?></td>
                                    <td>
                                        <?php if ($valoracion['estado'] == 'pendiente'): ?>
                                            <span class="badge bg-warning text-dark">Pendiente</span>
                                        <?php elseif ($valoracion['estado'] == 'aprobada'): ?>
                                            <span class="badge bg-success">Aprobada</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Rechazada</span>
                                        <?php endif; ?>
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