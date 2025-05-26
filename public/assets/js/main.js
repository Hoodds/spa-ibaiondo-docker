function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

function initPopovers() {
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
}

function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;

    let isValid = true;
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');

    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });

    return isValid;
}

function showAlert(message, type = 'success') {
    const alertContainer = document.createElement('div');
    alertContainer.className = `alert alert-${type} alert-dismissible fade show`;
    alertContainer.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    const container = document.querySelector('.container');
    if (container) {
        container.insertBefore(alertContainer, container.firstChild);

        setTimeout(() => {
            alertContainer.classList.remove('show');
            setTimeout(() => alertContainer.remove(), 150);
        }, 5000);
    }
}

function initAdminCollapses() {
    const toggleButtons = document.querySelectorAll('.toggle-collapse');
    const collapseElements = document.querySelectorAll('.collapse');

    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-bs-target');

            collapseElements.forEach(collapse => {
                if ('#' + collapse.id !== target && collapse.classList.contains('show')) {
                    const bsCollapse = bootstrap.Collapse.getInstance(collapse);
                    if (bsCollapse) {
                        bsCollapse.hide();
                    }
                }
            });
        });
    });

    const fechasReserva = document.querySelectorAll('.fecha-reserva');
    if (fechasReserva.length > 0) {
        fechasReserva.forEach(fechaInput => {
            fechaInput.addEventListener('change', function() {
                const reservaId = this.dataset.reservaId;
                const servicioId = this.dataset.servicioId;
                const fechaSeleccionada = this.value;

                if (!fechaSeleccionada) return;

                fetch(`/spa-ibaiondo/public/index.php/reservas/disponibilidad?id_servicio=${servicioId}&fecha=${fechaSeleccionada}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }

                        const trabajadorSelect = document.getElementById(`trabajador${reservaId}`);
                        const horaSelect = document.getElementById(`hora${reservaId}`);
                        const trabajadorActual = trabajadorSelect.dataset.trabajadorActual;
                        const horaActual = horaSelect.dataset.horaActual;

                        trabajadorSelect.innerHTML = '';
                        horaSelect.innerHTML = '';

                        if (data.length === 0) {
                            trabajadorSelect.innerHTML = '<option value="">No hay trabajadores disponibles</option>';
                            horaSelect.innerHTML = '<option value="">No hay horarios disponibles</option>';
                            return;
                        }

                        const fechaActual = document.getElementById(`fecha${reservaId}`).defaultValue;
                        const mantenerSeleccion = (fechaActual === fechaSeleccionada);

                        let trabajadorEncontrado = false;
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id_trabajador;
                            option.textContent = item.nombre_trabajador;
                            option.dataset.horas = JSON.stringify(item.horas_disponibles);

                            if (mantenerSeleccion && item.id_trabajador == trabajadorActual) {
                                option.selected = true;
                                trabajadorEncontrado = true;

                                cargarHorarios(horaSelect, item.horas_disponibles, horaActual, mantenerSeleccion);
                            }

                            trabajadorSelect.appendChild(option);
                        });

                        if (!trabajadorEncontrado && data.length > 0) {
                            trabajadorSelect.selectedIndex = 0;
                            cargarHorarios(horaSelect, JSON.parse(trabajadorSelect.options[0].dataset.horas), null, false);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al obtener disponibilidad. Por favor, inténtalo de nuevo.');
                    });
            });
        });
    }

    const trabajadoresReserva = document.querySelectorAll('.trabajador-reserva');
    if (trabajadoresReserva.length > 0) {
        trabajadoresReserva.forEach(trabajadorSelect => {
            trabajadorSelect.addEventListener('change', function() {
                if (!this.value) return;

                const reservaId = this.id.replace('trabajador', '');
                const horaSelect = document.getElementById(`hora${reservaId}`);
                const selectedOption = this.options[this.selectedIndex];

                if (selectedOption.dataset.horas) {
                    const horasDisponibles = JSON.parse(selectedOption.dataset.horas);
                    cargarHorarios(horaSelect, horasDisponibles, null, false);
                }
            });
        });
    }

    const nuevaFecha = document.getElementById('nuevaFecha');
    if (nuevaFecha) {
        const hoy = new Date().toISOString().split('T')[0];
        nuevaFecha.min = hoy;
    }

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

    const reservaButtons = document.querySelectorAll('[data-bs-target^="#editarReserva"]');
    if (reservaButtons.length > 0) {
        reservaButtons.forEach(button => {
            button.addEventListener('click', function() {
                const reservaId = this.dataset.bsTarget.replace('#editarReserva', '');
                const fechaInput = document.getElementById(`fecha${reservaId}`);

                if (fechaInput) {
                    setTimeout(() => {
                        const event = new Event('change');
                        fechaInput.dispatchEvent(event);
                    }, 200);
                }
            });
        });
    }
}

function cargarHorarios(horaSelect, horasDisponibles, horaActual, mantenerHora) {
    horaSelect.innerHTML = '';

    if (horasDisponibles.length === 0) {
        const option = document.createElement('option');
        option.value = '';
        option.textContent = 'No hay horarios disponibles';
        horaSelect.appendChild(option);
        return;
    }

    let horaEncontrada = false;

    horasDisponibles.forEach(hora => {
        const option = document.createElement('option');
        option.value = hora;
        option.textContent = hora;

        if (mantenerHora && hora === horaActual) {
            option.selected = true;
            horaEncontrada = true;
        }

        horaSelect.appendChild(option);
    });

    if (!horaEncontrada && horasDisponibles.length > 0) {
        horaSelect.selectedIndex = 0;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    initTooltips();
    initPopovers();
    initAdminCollapses();

    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(form.id)) {
                e.preventDefault();
                showAlert('Por favor, completa todos los campos requeridos.', 'danger');
            }
        });
    });

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();

            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});

const nuevaFecha = document.getElementById('nuevaFecha');
const nuevoTrabajador = document.getElementById('nuevoTrabajador');
const nuevaHora = document.getElementById('nuevaHora');
const nuevoServicio = document.getElementById('nuevoServicio');

let disponibilidadActual = [];

function actualizarHorasDisponibles() {
    if (!nuevaFecha.value || !nuevoServicio.value) {
        return;
    }

    nuevaHora.innerHTML = '<option value="">Cargando disponibilidad...</option>';
    nuevaHora.disabled = true;
    nuevoTrabajador.disabled = true;

    fetch(`${window.location.origin}/spa-ibaiondo/public/index.php/reservas/disponibilidad?id_servicio=${nuevoServicio.value}&fecha=${nuevaFecha.value}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            disponibilidadActual = data;

            nuevoTrabajador.innerHTML = '<option value="">Seleccionar trabajador</option>';

            if (data.length === 0) {
                nuevoTrabajador.disabled = true;
                nuevaHora.disabled = true;
                nuevaHora.innerHTML = '<option value="">No hay disponibilidad</option>';
                return;
            }

            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id_trabajador;
                option.textContent = item.nombre_trabajador;
                option.dataset.horas = JSON.stringify(item.horas_disponibles);
                nuevoTrabajador.appendChild(option);
            });

            nuevoTrabajador.disabled = false;
            nuevaHora.disabled = true;
            nuevaHora.innerHTML = '<option value="">Seleccione un trabajador</option>';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al obtener disponibilidad. Por favor, inténtalo de nuevo.');
        });
}

if (nuevaFecha && nuevoServicio) {
    nuevaFecha.addEventListener('change', actualizarHorasDisponibles);
    nuevoServicio.addEventListener('change', actualizarHorasDisponibles);
}

if (nuevoTrabajador) {
    nuevoTrabajador.addEventListener('change', function() {
        if (!this.value) {
            nuevaHora.disabled = true;
            nuevaHora.innerHTML = '<option value="">Seleccione un trabajador</option>';
            return;
        }

        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.dataset.horas) {
            const horasDisponibles = JSON.parse(selectedOption.dataset.horas);

            nuevaHora.innerHTML = '<option value="">Seleccionar hora</option>';

            if (horasDisponibles.length === 0) {
                nuevaHora.innerHTML = '<option value="">No hay horas disponibles</option>';
                nuevaHora.disabled = true;
                return;
            }

            horasDisponibles.forEach(hora => {
                const option = document.createElement('option');
                option.value = hora;
                option.textContent = hora;
                nuevaHora.appendChild(option);
            });

            nuevaHora.disabled = false;
        }
    });
}