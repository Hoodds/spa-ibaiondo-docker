<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="text-center mb-5">Contacto</h1>

            <div class="row mb-5">
                <div class="col-md-4 text-center mb-4 mb-md-0">
                    <div class="bg-light p-4 rounded h-100">
                        <i class="fas fa-map-marker-alt fa-2x text-primary mb-3"></i>
                        <h5>Dirección</h5>
                        <p class="mb-0">
                            <a href="https://www.google.com/maps/search/?api=1&query=Calle+Principal+123,+Ciudad,+48001" class="text-dark" target="_blank">
                                Calle Principal 123<br>Ciudad, CP 48001
                            </a>
                        </p>
                    </div>
                </div>
                <div class="col-md-4 text-center mb-4 mb-md-0">
                    <div class="bg-light p-4 rounded h-100">
                        <i class="fas fa-phone fa-2x text-primary mb-3"></i>
                        <h5>Teléfono</h5>
                        <p class="mb-0">
                            <a href="tel:+34946123456" class="text-dark">+34 946 123 456</a><br>
                            <a href="tel:+34688789012" class="text-dark">+34 688 789 012</a>
                        </p>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="bg-light p-4 rounded h-100">
                        <i class="fas fa-envelope fa-2x text-primary mb-3"></i>
                        <h5>Email</h5>
                        <p class="mb-0">
                            <a href="mailto:info@spaibaiondo.com" class="text-dark">info@spaibaiondo.com</a><br>
                            <a href="mailto:reservas@spaibaiondo.com" class="text-dark">reservas@spaibaiondo.com</a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-4">Envíanos un mensaje</h4>

                    <form action="<?= Helper::url('contacto/enviar') ?>" method="POST" id="contactForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="asunto" class="form-label">Asunto</label>
                            <input type="text" class="form-control" id="asunto" name="asunto" required>
                        </div>
                        <div class="mb-3">
                            <label for="mensaje" class="form-label">Mensaje</label>
                            <textarea class="form-control" id="mensaje" name="mensaje" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid px-0 mt-5">
    <div class="map-container">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2905.3706935903716!2d-2.9344233!3d43.2630126!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd4e4fd609b7a6f9%3A0x5bf2a8f7d3dd4957!2sBilbao%2C%20Vizcaya!5e0!3m2!1ses!2ses!4v1646579863754!5m2!1ses!2ses" 
                width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>
</div>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
    alert('Gracias por tu mensaje. Te contactaremos pronto.');
});
</script>