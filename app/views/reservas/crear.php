<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h2 class="card-title mb-0">Reservar Servicio</h2>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                        <img src="<?= Helper::asset('images/servicios/' . ($servicio['imagen'] ?? 'servicio-default.jpg')) ?>"
                            alt="<?= Helper::e($servicio['nombre']) ?>"
                            class="card-img-top"
                            onerror="if(this.src.indexOf('servicio-default.jpg') === -1) this.src='<?= Helper::asset('images/servicios/servicio-default.jpg') ?>';">
                        </div>
                        <div class="col-md-8">
                            <h4><?= Helper::e($servicio['nombre']) ?></h4>
                            <p><?= substr(Helper::e($servicio['descripcion']), 0, 150) ?>...</p>
                            <div class="d-flex">
                                <span class="badge bg-primary me-2">
                                    <i class="far fa-clock"></i> <?= $servicio['duracion'] ?> minutos
                                </span>
                                <span class="badge bg-info">
                                    <i class="fas fa-tag"></i> <?= Helper::formatPrice($servicio['precio']) ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <form action="<?= Helper::url('reservas/crear') ?>" method="post" id="reservaForm">
                        <input type="hidden" name="id_servicio" value="<?= $servicio['id'] ?>">

                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required min="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="id_trabajador" class="form-label">Profesional</label>
                            <select class="form-select" id="id_trabajador" name="id_trabajador" required disabled>
                                <option value="">Selecciona un profesional</option>
                            </select>
                            <div class="form-text">Primero selecciona una fecha para ver los profesionales disponibles.</div>
                        </div>

                        <div class="mb-3">
                            <label for="hora" class="form-label">Hora</label>
                            <select class="form-select" id="hora" name="hora" required disabled>
                                <option value="">Selecciona una hora</option>
                            </select>
                            <div class="form-text">Primero selecciona un profesional para ver las horas disponibles.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= Helper::url('servicios/' . $servicio['id']) ?>" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary" id="btnReservar" disabled>Confirmar Reserva</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fechaInput = document.getElementById('fecha');
    const trabajadorSelect = document.getElementById('id_trabajador');
    const horaSelect = document.getElementById('hora');
    const btnReservar = document.getElementById('btnReservar');

    fechaInput.addEventListener('change', function() {
        if (this.value) {
            fetch('<?= Helper::url('reservas/disponibilidad') ?>?id_servicio=<?= $servicio['id'] ?>&fecha=' + this.value)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    trabajadorSelect.innerHTML = '<option value="">Selecciona un profesional</option>';

                    if (data.length === 0) {
                        trabajadorSelect.disabled = true;
                        horaSelect.disabled = true;
                        btnReservar.disabled = true;
                        alert('No hay disponibilidad para esta fecha. Por favor, selecciona otra fecha.');
                        return;
                    }

                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id_trabajador;
                        option.textContent = item.nombre_trabajador;
                        option.dataset.horas = JSON.stringify(item.horas_disponibles);
                        trabajadorSelect.appendChild(option);
                    });

                    trabajadorSelect.disabled = false;
                    horaSelect.disabled = true;
                    btnReservar.disabled = true;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al obtener disponibilidad. Por favor, inténtalo de nuevo.');
                });
        } else {
            trabajadorSelect.disabled = true;
            horaSelect.disabled = true;
            btnReservar.disabled = true;
        }
    });

    trabajadorSelect.addEventListener('change', function() {
        if (this.value) {
            const selectedOption = this.options[this.selectedIndex];
            const horasDisponibles = JSON.parse(selectedOption.dataset.horas);

            horaSelect.innerHTML = '<option value="">Selecciona una hora</option>';

            horasDisponibles.forEach(hora => {
                const option = document.createElement('option');
                option.value = hora;
                option.textContent = hora;
                horaSelect.appendChild(option);
            });

            horaSelect.disabled = false;
            btnReservar.disabled = true;
        } else {
            horaSelect.disabled = true;
            btnReservar.disabled = true;
        }
    });

    horaSelect.addEventListener('change', function() {
        btnReservar.disabled = !this.value;
    });
});
</script>