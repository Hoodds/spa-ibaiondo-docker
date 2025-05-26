-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS spa_docker DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;
USE spa;

-- Tabla de usuarios (clientes)
CREATE TABLE usuarios (
	id INT AUTO_INCREMENT PRIMARY KEY,
	nombre VARCHAR(100) NOT NULL,
	email VARCHAR(100) UNIQUE NOT NULL,
	contrasena VARCHAR(255) NOT NULL,
	fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Tabla de trabajadores
CREATE TABLE trabajadores (
	id INT AUTO_INCREMENT PRIMARY KEY,
	nombre VARCHAR(100) NOT NULL,
	email VARCHAR(100) UNIQUE NOT NULL,
	contrasena VARCHAR(255) NOT NULL,
	rol ENUM('admin', 'recepcionista', 'masajista', 'terapeuta') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Tabla de servicios
CREATE TABLE servicios (
	id INT AUTO_INCREMENT PRIMARY KEY,
	nombre VARCHAR(100) NOT NULL,
	descripcion TEXT NOT NULL,
	duracion INT NOT NULL,
	precio DECIMAL(10,2) NOT NULL,
	imagen VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Tabla de reservas
CREATE TABLE reservas (
	id INT AUTO_INCREMENT PRIMARY KEY,
	id_usuario INT,
	id_servicio INT,
	id_trabajador INT,
	fecha_hora DATETIME NOT NULL,
	estado ENUM('pendiente', 'confirmada', 'cancelada') NOT NULL,
	FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
	FOREIGN KEY (id_servicio) REFERENCES servicios(id),
	FOREIGN KEY (id_trabajador) REFERENCES trabajadores(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Tabla de mensajes
CREATE TABLE mensajes_contacto (
	id INT AUTO_INCREMENT PRIMARY KEY,
	nombre VARCHAR(255) NOT NULL,
	email VARCHAR(255) NOT NULL,
	asunto VARCHAR(255) NOT NULL,
	mensaje TEXT NOT NULL,
	fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Tabla de valoraciones
CREATE TABLE valoraciones (
	id INT AUTO_INCREMENT PRIMARY KEY,
	id_usuario INT NOT NULL,
	id_servicio INT NOT NULL,
	puntuacion INT NOT NULL CHECK (puntuacion BETWEEN 1 AND 5),
	comentario TEXT,
	fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	estado ENUM('pendiente', 'aprobada', 'rechazada') NOT NULL DEFAULT 'pendiente',
	FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
	FOREIGN KEY (id_servicio) REFERENCES servicios(id) ON DELETE CASCADE,
	UNIQUE KEY (id_usuario, id_servicio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Insertar datos en usuarios
INSERT INTO usuarios (nombre, email, contrasena) VALUES
('Juan Pérez', 'juan@example.com', '123'),
('María López', 'maria@example.com', '123'),
('Carlos Gómez', 'carlos@example.com', '123'),
('Ana Martínez', 'ana@example.com', '123'),
('Luis Ramírez', 'luis@example.com', '123'),
('Elena Torres', 'elena@example.com', '123'),
('Pedro Jiménez', 'pedro@example.com', '123'),
('Laura Sánchez', 'laura@example.com', '123'),
('David Ríos', 'david@example.com', '123'),
('Andrea Castillo', 'andrea@example.com', '123'),
('Jorge Medina', 'jorge@example.com', '123'),
('Silvia Vega', 'silvia@example.com', '123'),
('Fernando Herrera', 'fernando@example.com', '123'),
('Patricia Cruz', 'patricia@example.com', '123'),
('Alberto Flores', 'alberto@example.com', '123'),
('Gabriela Romero', 'gabriela@example.com', '123'),
('Manuel Ortega', 'manuel@example.com', '123'),
('Carmen Álvarez', 'carmen@example.com', '123'),
('Héctor Navarro', 'hector@example.com', '123'),
('Isabel Duarte', 'isabel@example.com', '123');

-- Insertar datos en trabajadores
INSERT INTO trabajadores (nombre, email, contrasena, rol) VALUES
('Admin General', 'admin@spa.com', '123', 'admin'),
('Ana Recepcionista', 'ana@spa.com', '123', 'recepcionista'),
('Carlos Masajista', 'carlos@spa.com', '123', 'masajista'),
('Pedro Masajista', 'pedro@spa.com', '123', 'masajista'),
('Lucía Terapeuta', 'lucia@spa.com', '123', 'terapeuta'),
('Miguel Terapeuta', 'miguel@spa.com', '123', 'terapeuta'),
('Sandra Recepcionista', 'sandra@spa.com', '123', 'recepcionista'),
('Daniel Masajista', 'daniel@spa.com', '123', 'masajista'),
('Raúl Terapeuta', 'raul@spa.com', '123', 'terapeuta'),
('Beatriz Recepcionista', 'beatriz@spa.com', '123', 'recepcionista');

-- Insertar datos en servicios
INSERT INTO servicios (nombre, descripcion, duracion, precio, imagen) VALUES
('Masaje relajante', 'Masaje con aceites esenciales.', 60, 50.00, NULL),
('Masaje terapéutico', 'Masaje profundo para aliviar tensiones.', 45, 55.00, NULL),
('Facial hidratante', 'Tratamiento facial con productos naturales.', 30, 40.00, NULL),
('Exfoliación corporal', 'Elimina células muertas con sales marinas.', 50, 65.00, NULL),
('Terapia con piedras calientes', 'Relajación con piedras volcánicas.', 75, 70.00, NULL),
('Drenaje linfático', 'Masaje suave para eliminar toxinas.', 40, 45.00, NULL),
('Reflexología podal', 'Terapia en puntos estratégicos del pie.', 35, 35.00, NULL),
('Aromaterapia', 'Masaje con aceites esenciales personalizados.', 60, 60.00, NULL),
('Envoltura de chocolate', 'Tratamiento corporal hidratante.', 55, 75.00, NULL),
('Circuito Spa', 'Acceso a sauna, jacuzzi y piscina climatizada.', 90, 85.00, NULL);

-- Insertar datos en reservas
INSERT INTO reservas (id_usuario, id_servicio, id_trabajador, fecha_hora, estado) VALUES
(1, 1, 3, '2024-02-15 10:00:00', 'confirmada'),
(2, 5, 5, '2024-03-10 15:30:00', 'cancelada'),
(3, 3, 6, '2024-01-20 09:00:00', 'confirmada'),
(4, 2, 4, '2024-04-05 14:00:00', 'pendiente'),
(5, 7, 9, '2024-05-22 16:00:00', 'confirmada'),
(6, 6, 4, '2024-02-08 12:30:00', 'confirmada'),
(7, 8, 8, '2024-03-25 11:45:00', 'cancelada'),
(8, 4, 4, '2024-06-14 13:15:00', 'pendiente'),
(9, 10, 4, '2024-07-01 17:00:00', 'confirmada'),
(10, 9, 5, '2024-08-19 18:30:00', 'pendiente');

-- Insertar datos en valoraciones
INSERT INTO valoraciones (id_usuario, id_servicio, puntuacion, comentario) VALUES
(1, 1, 5, 'Excelente masaje, muy relajante. La terapeuta fue muy profesional.'),
(2, 1, 4, 'Muy buen servicio, aunque el ambiente podría ser más tranquilo.'),
(3, 2, 5, 'El masaje terapéutico alivió mi dolor de espalda. ¡Increíble!'),
(4, 3, 4, 'El facial dejó mi piel radiante. Recomendado.'),
(5, 5, 5, 'La terapia con piedras calientes es lo mejor que he probado.'),
(6, 4, 3, 'La exfoliación fue buena, pero esperaba más del tratamiento.'),
(7, 6, 5, 'El drenaje linfático me ayudó mucho con la retención de líquidos.'),
(8, 8, 4, 'La aromaterapia fue muy relajante, volveré pronto.'),
(9, 10, 5, 'El circuito spa es completo y las instalaciones están impecables.'),
(10, 9, 4, 'La envoltura de chocolate dejó mi piel suave e hidratada.');
