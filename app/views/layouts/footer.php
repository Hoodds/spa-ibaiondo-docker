    <footer class="text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Spa Ibaiondo</h5>
                    <p>Tu espacio de bienestar y relajación en el corazón de la ciudad.</p>
                </div>
                <div class="col-md-4">
                    <h5>Contacto</h5>
                    <address>
                        <a href="https://www.google.com/maps/search/?api=1&query=Calle+Principal+123,+Ciudad" class="text-white" target="_blank">
                            <i class="fas fa-map-marker-alt"></i> Calle Principal 123, Ciudad
                        </a><br>
                        <a href="tel:+34946123456" class="text-white">
                            <i class="fas fa-phone"></i> +34 946 123 456
                        </a><br>
                        <a href="mailto:info@spaibaiondo.com" class="text-white">
                            <i class="fas fa-envelope"></i> info@spaibaiondo.com
                        </a>
                    </address>
                </div>
                <div class="col-md-4">
                    <h5>Síguenos</h5>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/spaibaiondo" class="text-white me-2" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.instagram.com/spaibaiondo" class="text-white me-2" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.twitter.com/spaibaiondo" class="text-white me-2" target="_blank"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; <?= date('Y') ?> Spa Ibaiondo. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Scripts personalizados -->
    <script src="<?= Helper::url('assets/js/main.js') ?>"></script>
</body>
</html>