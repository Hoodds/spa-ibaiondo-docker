<?php
// Rutas públicas
$router->add('GET', '/', 'HomeController', 'index');
$router->add('GET', '/servicios', 'ServicioController', 'listar');
$router->add('GET', '/servicios/{id}', 'ServicioController', 'mostrar');
$router->add('GET', '/contacto', 'HomeController', 'contacto');
$router->add('GET', '/login', 'UsuarioController', 'showLogin');
$router->add('POST', '/login', 'UsuarioController', 'login');
$router->add('GET', '/registro', 'UsuarioController', 'showRegistro');
$router->add('POST', '/registro', 'UsuarioController', 'registro');
$router->add('POST', '/contacto/enviar', 'ContactoController', 'enviar');

// Rutas para usuarios autenticados
$router->add('GET', '/perfil', 'UsuarioController', 'perfil');
$router->add('POST', '/perfil', 'UsuarioController', 'perfil');
$router->add('GET', '/reservas', 'ReservaController', 'misReservas');
$router->add('GET', '/reservas/crear/{id}', 'ReservaController', 'showCrear');
$router->add('POST', '/reservas/crear', 'ReservaController', 'crear');
$router->add('GET', '/reservas/{id}/cancelar', 'ReservaController', 'cancelar');
$router->add('GET', '/logout', 'UsuarioController', 'logout');
$router->add('GET', '/reservas/disponibilidad', 'ReservaController', 'getDisponibilidad');

// Rutas para valoraciones
$router->add('GET', '/servicios/mis-valoraciones', 'ServicioController', 'misValoraciones');
$router->add('POST', '/servicios/{id}/valorar', 'ServicioController', 'valorar');
$router->add('GET', '/servicios/{id}/valorar', 'ServicioController', 'valorar');
$router->add('GET', '/servicios/valoracion/{id}/eliminar', 'ServicioController', 'eliminarValoracion');

// Rutas para trabajadores
$router->add('GET', '/trabajador/login', 'TrabajadorController', 'login');
$router->add('POST', '/trabajador/login', 'TrabajadorController', 'login');
$router->add('GET', '/trabajador/dashboard', 'TrabajadorController', 'dashboard');
$router->add('GET', '/trabajador/reservas', 'TrabajadorController', 'misReservas');
$router->add('POST', '/trabajador/reservas/crear', 'TrabajadorController', 'crearReserva');
$router->add('POST', '/trabajador/reservas/editar', 'TrabajadorController', 'editarReserva');
$router->add('GET', '/trabajador/reservas/{id}/completar', 'TrabajadorController', 'completarReserva');
$router->add('GET', '/trabajador/reservas/{id}/cancelar', 'TrabajadorController', 'cancelarReserva');
$router->add('GET', '/trabajador/valoraciones', 'TrabajadorController', 'misValoraciones');
$router->add('GET', '/trabajador/logout', 'TrabajadorController', 'logout');
$router->add('GET', '/trabajador/servicios', 'TrabajadorController', 'listarServicios');

// Rutas para administradores
$router->add('GET', '/admin', 'AdminController', 'dashboard');
$router->add('GET', '/admin/reservas', 'AdminController', 'listarReservas');
$router->add('POST', '/admin/reservas/crear', 'AdminController', 'crearReserva');
$router->add('POST', '/admin/reservas/editar', 'ReservaController', 'editar');
$router->add('GET', '/admin/reservas/eliminar/{id}', 'AdminController', 'eliminarReserva');
$router->add('GET', '/admin/servicios', 'AdminController', 'listarServicios');
$router->add('POST', '/admin/servicios/editar', 'ServicioController', 'editar');
$router->add('POST', '/admin/servicios/crear', 'AdminController', 'crearServicio');
$router->add('GET', '/admin/servicios/eliminar/{id}', 'AdminController', 'eliminarServicio');
$router->add('GET', '/admin/usuarios', 'AdminController', 'listarUsuarios');
$router->add('POST', '/admin/usuarios/editar', 'UsuarioController', 'editar');
$router->add('POST', '/admin/usuarios/crear', 'AdminController', 'crearUsuario');
$router->add('GET', '/admin/usuarios/eliminar/{id}', 'AdminController', 'eliminarUsuario');
$router->add('GET', '/admin/trabajadores', 'AdminController', 'listarTrabajadores');
$router->add('POST', '/admin/trabajadores/editar', 'TrabajadorController', 'editar');
$router->add('POST', '/admin/trabajadores/crear', 'AdminController', 'crearTrabajador');
$router->add('GET', '/admin/trabajadores/eliminar/{id}', 'AdminController', 'eliminarTrabajador');
$router->add('GET', '/admin/valoraciones', 'AdminController', 'listarValoraciones');
$router->add('GET', '/admin/valoraciones/pendientes', 'AdminController', 'valoracionesPendientes');
$router->add('GET', '/admin/valoraciones/{id}/aprobar', 'AdminController', 'aprobarValoracion');
$router->add('GET', '/admin/valoraciones/{id}/rechazar', 'AdminController', 'rechazarValoracion');
$router->add('GET', '/admin/valoraciones/{id}/eliminar', 'AdminController', 'eliminarValoracion');
