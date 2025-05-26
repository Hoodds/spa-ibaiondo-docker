<div class="hero-section py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4">Bienvenido a Spa Ibaiondo</h1>
                <p class="lead">Tu espacio de bienestar y relajación en el corazón de la ciudad.</p>
                <p>Descubre nuestros tratamientos exclusivos y déjate mimar por nuestros profesionales.</p>
                <a href="<?= Helper::url('servicios') ?>" class="btn btn-light btn-lg">Ver Servicios</a>
            </div>
            <div class="col-md-6">
                <img src="<?= Helper::asset('images/ini.jpg') ?>"
                    alt="Spa Ibaiondo"
                    class="img-fluid rounded-circle w-50 mx-auto d-block">
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <h2 class="text-center mb-5">Nuestros Servicios Destacados</h2>

    <div class="row">
        <?php foreach (array_slice($serviciosDestacados, 0, 3) as $servicio): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                <img src="<?= Helper::asset('images/servicios/' . ($servicio['imagen'] ?? 'servicio-default.jpg')) ?>"
                     alt="<?= Helper::e($servicio['nombre']) ?>"
                     class="card-img-top"
                     onerror="if(this.src.indexOf('servicio-default.jpg') === -1) this.src='<?= Helper::asset('images/servicios/servicio-default.jpg') ?>';">
                    <div class="card-body">
                        <h5 class="card-title"><?= Helper::e($servicio['nombre']) ?></h5>
                        <p class="card-text"><?= substr(Helper::e($servicio['descripcion']), 0, 100) ?>...</p>
                        <p class="card-text">
                            <small class="text-muted">
                                <i class="far fa-clock"></i> <?= $servicio['duracion'] ?> minutos |
                                <strong><?= Helper::formatPrice($servicio['precio']) ?></strong>
                            </small>
                        </p>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <a href="<?= Helper::url('servicios/' . $servicio['id']) ?>" class="btn btn-outline-primary">Ver Detalles</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="text-center mt-4">
        <a href="<?= Helper::url('servicios') ?>" class="btn btn-primary">Ver Todos los Servicios</a>
    </div>
</div>

<div class="bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <img src="<?= Helper::asset('images/about-spa.jpg') ?>" alt="Sobre Nosotros" class="img-fluid rounded">
            </div>
            <div class="col-md-6">
                <h2>Sobre Spa Ibaiondo</h2>
                <p>En Spa Ibaiondo nos dedicamos a proporcionar experiencias de bienestar excepcionales en un entorno de lujo y tranquilidad.</p>
                <p>Nuestro equipo de profesionales altamente cualificados está comprometido con ofrecerte los mejores tratamientos personalizados para rejuvenecer tu cuerpo y mente.</p>
                <p>Utilizamos productos de la más alta calidad y técnicas innovadoras para garantizar resultados óptimos en cada visita.</p>
                <a href="<?= Helper::url('contacto') ?>" class="btn btn-outline-primary">Contactar</a>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <h2 class="text-center mb-5">¿Por qué elegirnos?</h2>

    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="p-4 bg-white rounded shadow-sm h-100">
                <i class="fas fa-award fa-3x text-primary mb-3"></i>
                <h4>Profesionales Certificados</h4>
                <p>Nuestro equipo cuenta con amplia experiencia y certificaciones en las técnicas más avanzadas.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="p-4 bg-white rounded shadow-sm h-100">
                <i class="fas fa-spa fa-3x text-primary mb-3"></i>
                <h4>Productos Premium</h4>
                <p>Utilizamos exclusivamente productos naturales y orgánicos de la más alta calidad.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="p-4 bg-white rounded shadow-sm h-100">
                <i class="fas fa-heart fa-3x text-primary mb-3"></i>
                <h4>Atención Personalizada</h4>
                <p>Cada tratamiento se adapta a tus necesidades específicas para maximizar los resultados.</p>
            </div>
        </div>
    </div>
</div>