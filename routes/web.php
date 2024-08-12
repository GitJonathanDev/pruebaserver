<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrarController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TipoUsuarioController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VendedorController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\MembresiaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ConsumirServicioController;
use App\Http\Controllers\VentaClienteController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\CompraReportController;
use App\Http\Controllers\VentaReportController;
use App\Http\Controllers\PrecioServicioController;
use App\Http\Controllers\EstadisticasController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SearchController;

// Página principal y vistas de usuario
Route::view('/', 'principal');
Route::view('/cliente', 'principalCliente')->name('cliente')->middleware("tipo");
// Route::view('/vendedor', 'index')->name('encargado'); //->middleware('auth.encargado');
// Route::view('/administrador', 'indexAdmin')->name('admin'); //->middleware('auth.admin')->name('admin');

Route::get('/vendedor', [MenuController::class, 'index'])->name('encargado');
Route::get('/administrador', [MenuController::class, 'index'])->name('admin');


// Autenticación
Route::middleware('guest')->group(function () {
    Route::get('/Registrar/index', [RegistrarController::class, 'create'])->name('registrar.index');
    Route::post('/Registrar/create', [RegistrarController::class, 'store'])->name('registrar.create');
});

Route::get('/login/index', [LoginController::class, 'create'])->name('login.index');
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login/create', [LoginController::class, 'store'])->name('login.create');
Route::post('/login/clienteVista', [LoginController::class, 'vista'])->name('principalCliente');



Route::middleware([\App\Http\Middleware\VerificarAutenticacion::class])->group(function () {
Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');


Route::middleware('auth')->group(function () {
    Route::get('/mis-membresias', [MembresiaController::class, 'mostrarMembresias'])->name('mis-membresias');
    Route::view('/cliente', 'principalCliente')->name('cliente');
    Route::post('/login/logout', [LoginController::class, 'destroy'])->name('login.destroy');
});



Route::prefix('tipoUsuario')->group(function () {
    Route::get('index', [TipoUsuarioController::class, 'index'])->name('tipoUsuario.index');
    Route::get('create', [TipoUsuarioController::class, 'create'])->name('tipoUsuario.create');
    Route::post('store', [TipoUsuarioController::class, 'store'])->name('tipoUsuario.store');
    Route::get('edit/{codTipoUsuario}', [TipoUsuarioController::class, 'edit'])->name('tipoUsuario.edit');
    Route::put('update/{codTipoUsuario}', [TipoUsuarioController::class, 'update'])->name('tipoUsuario.update');
    Route::delete('eliminar/{codTipoUsuario}', [TipoUsuarioController::class, 'destroy'])->name('tipoUsuario.destroy');
    // Asegúrate de definir el método 'buscarClientes' en tu controlador si se usa
    Route::get('buscar', [TipoUsuarioController::class, 'buscarClientes'])->name('tipoUsuario.buscar');
});

// Gestionar usuario
Route::prefix('usuario')->group(function () {
    Route::get('index', [UsuarioController::class, 'index'])->name('usuario.index');
    Route::get('create', [UsuarioController::class, 'create'])->name('usuario.create');
    Route::post('store', [UsuarioController::class, 'store'])->name('usuario.store');
    Route::get('edit/{codUsuario}', [UsuarioController::class, 'edit'])->name('usuario.edit');
    Route::put('update/{codUsuario}', [UsuarioController::class, 'update'])->name('usuario.update');
    Route::delete('eliminar/{codUsuario}', [UsuarioController::class, 'destroy'])->name('usuario.destroy');
    Route::get('buscar', [UsuarioController::class, 'buscarClientes'])->name('usuario.buscar');
});

// Gestionar vendedor
Route::prefix('vendedor')->group(function () {
    Route::get('index', [VendedorController::class, 'index'])->name('vendedor.index');
    Route::get('create', [VendedorController::class, 'create'])->name('vendedor.create');
    Route::post('store', [VendedorController::class, 'store'])->name('vendedor.store');
    Route::get('edit/{carnetIdentidad}', [VendedorController::class, 'edit'])->name('vendedor.edit');
    Route::put('update/{carnetIdentidad}', [VendedorController::class, 'update'])->name('vendedor.update');
    Route::delete('eliminar/{carnetIdentidad}', [VendedorController::class, 'destroy'])->name('vendedor.destroy');
    Route::post('ci-ya-existe', [VendedorController::class, 'ciYaExiste'])->name('ci-ya-existe');
});

// Gestionar cliente
Route::prefix('cliente')->group(function () {
    Route::get('index', [ClienteController::class, 'index'])->name('cliente.index');
    Route::get('create', [ClienteController::class, 'create'])->name('cliente.create');
    Route::post('store', [ClienteController::class, 'store'])->name('cliente.store');
    Route::get('edit/{carnetIdentidad}', [ClienteController::class, 'edit'])->name('cliente.edit');
    Route::put('update/{carnetIdentidad}', [ClienteController::class, 'update'])->name('cliente.update');
    Route::delete('eliminar/{carnetIdentidad}', [ClienteController::class, 'destroy'])->name('cliente.destroy');
    Route::post('ci-ya-existe', [ClienteController::class, 'ciYaExiste'])->name('ci-ya-existe');
    Route::get('buscar', [ClienteController::class, 'index'])->name('clientes.buscar'); // Ajustado para llamar a index
    Route::get('seleccionCliente/{carnetIdentidad}', [MembresiaController::class, 'seleccionCliente'])->name('cliente.seleccion'); // Asegúrate de que esta ruta sea correcta
});

// Rutas para gestionar membresía
Route::get('/membresia/index', [MembresiaController::class, 'index'])->name('membresia.index');
Route::get('/membresia/create', [MembresiaController::class, 'create'])->name('membresia.create');
Route::post('/membresia/store', [MembresiaController::class, 'store'])->name('membresia.store');
Route::get('/membresia/edit/{codMembresia}', [MembresiaController::class, 'edit'])->name('membresia.edit');
Route::get('/membresia/show/{codMembresia}', [MembresiaController::class, 'show'])->name('membresia.show');
Route::put('/membresia/update/{codMembresia}', [MembresiaController::class, 'update'])->name('membresia.update');
Route::delete('/membresia/delete/{codMembresia}', [MembresiaController::class, 'destroy'])->name('membresia.destroy');
Route::get('/membresia/buscar-cliente', [MembresiaController::class, 'buscarCliente'])->name('membresia.buscar');
Route::get('/cliente/seleccion/{codClienteF}', [MembresiaController::class, 'seleccionCliente'])->name('membresia.seleccionCliente');


Route::get('/estadisticas/index', [EstadisticasController::class, 'index'])->name('inicio.estadistica');


// Gestionar pago
Route::prefix('pago')->group(function () {
    Route::get('index', [PagoController::class, 'index'])->name('pago.index');
    Route::get('create', [PagoController::class, 'create'])->name('pago.create');
    Route::post('store', [PagoController::class, 'store'])->name('pago.store');
    Route::get('edit/{codPago}', [PagoController::class, 'edit'])->name('pago.edit');
    Route::put('update/{codPago}', [PagoController::class, 'update'])->name('pago.update');
    Route::delete('eliminar/{codPago}', [PagoController::class, 'destroy'])->name('pago.destroy');
});

Route::view('/pago', 'GestionarPago.pago');
Route::post('/consumirServicio', [ConsumirServicioController::class, 'RecolectarDatos']);
Route::post('/consultar', [ConsumirServicioController::class, 'ConsultarEstado']);

// Gestionar categoría
Route::prefix('categoria')->group(function () {
    Route::get('index', [CategoriaController::class, 'index'])->name('categoria.index');
    Route::get('index2', [CategoriaController::class, 'index2'])->name('categoria.index2');
    Route::get('create', [CategoriaController::class, 'create'])->name('categoria.create');
    Route::post('store', [CategoriaController::class, 'store'])->name('categoria.store');
    Route::get('edit/{codCategoria}', [CategoriaController::class, 'edit'])->name('categoria.edit');
    Route::put('update/{codCategoria}', [CategoriaController::class, 'update'])->name('categoria.update');
    Route::delete('eliminar/{codCategoria}', [CategoriaController::class, 'destroy'])->name('categoria.destroy');
});
// Gestionar producto
Route::prefix('producto')->group(function () {
    Route::get('index', [ProductoController::class, 'index'])->name('producto.index');
    Route::get('create', [ProductoController::class, 'create'])->name('producto.create');
    Route::post('store', [ProductoController::class, 'store'])->name('producto.store');
    Route::get('edit/{codProducto}', [ProductoController::class, 'edit'])->name('producto.edit');
    Route::put('update/{codProducto}', [ProductoController::class, 'update'])->name('producto.update');
    Route::delete('eliminar/{codProducto}', [ProductoController::class, 'destroy'])->name('producto.destroy');
    Route::get('buscar', [ProductoController::class, 'buscar'])->name('producto.buscar');
});

// Gestionar proveedor
Route::prefix('proveedor')->group(function () {
    Route::get('index', [ProveedorController::class, 'index'])->name('proveedor.index');
    Route::get('create', [ProveedorController::class, 'create'])->name('proveedor.create');
    Route::post('store', [ProveedorController::class, 'store'])->name('proveedor.store');
    Route::get('edit/{codProveedor}', [ProveedorController::class, 'edit'])->name('proveedor.edit');
    Route::put('update/{codProveedor}', [ProveedorController::class, 'update'])->name('proveedor.update');
    Route::delete('eliminar/{codProveedor}', [ProveedorController::class, 'destroy'])->name('proveedor.destroy');
});

// Rutas para gestionar horario
Route::get('/horario/index', [HorarioController::class, 'index'])->name('horario.index');
Route::get('/horario/create', [HorarioController::class, 'create'])->name('horario.create');
Route::post('/horario/store', [HorarioController::class, 'store'])->name('horario.store');
Route::get('/horario/edit/{codHorario}', [HorarioController::class, 'edit'])->name('horario.edit');
Route::put('/horario/update/{codHorario}', [HorarioController::class, 'update'])->name('horario.update');
Route::delete('/horario/eliminar/{codHorario}', [HorarioController::class, 'destroy'])->name('horario.destroy');

// Rutas para gestionar servicios
Route::prefix('servicio')->group(function () {
    Route::get('/index', [ServicioController::class, 'index'])->name('servicio.index');
    Route::get('/create', [ServicioController::class, 'create'])->name('servicio.create');
    Route::post('/store', [ServicioController::class, 'store'])->name('servicio.store');
    Route::get('/edit/{codServicio}', [ServicioController::class, 'edit'])->name('servicio.edit');
    Route::put('/update/{codServicio}', [ServicioController::class, 'update'])->name('servicio.update');
    Route::delete('/eliminar/{codServicio}', [ServicioController::class, 'destroy'])->name('servicio.destroy');
    Route::post('/registrar-detalle/{codServicio}/{codHorario}', [ServicioController::class, 'registrarDetalleServicio'])->name('servicio.registrar.detalle');
});

// Ruta para buscar productos
Route::get('/buscar', [ProductoController::class, 'buscar'])->name('productos.buscar');

// Rutas para manejar precios de servicio
Route::get('/precioServicio/index', [PrecioServicioController::class, 'index'])->name('precioServicio.index');
Route::get('/precioServicio/create', [PrecioServicioController::class, 'create'])->name('precioServicio.create');
Route::post('/precioServicio/store', [PrecioServicioController::class, 'store'])->name('precioServicio.store');
Route::get('/precioServicio/edit/{codPrecioServicio}', [PrecioServicioController::class, 'edit'])->name('precioServicio.edit');
Route::put('/precioServicio/update/{codPrecioServicio}', [PrecioServicioController::class, 'update'])->name('precioServicio.update');
Route::delete('/precioServicio/eliminar/{codPrecioServicio}', [PrecioServicioController::class, 'destroy'])->name('precioServicio.destroy');


// Gestionar compra
Route::prefix('compra')->group(function () {
    Route::get('index', [CompraController::class, 'index'])->name('compra.index');
    Route::get('create', [CompraController::class, 'create'])->name('compra.create');
    Route::post('store', [CompraController::class, 'store'])->name('compra.store');
    Route::get('{codCompra}', [CompraController::class, 'show'])->name('compra.show');
    Route::get('{codCompra}/edit', [CompraController::class, 'edit'])->name('compra.edit');
    Route::put('{codCompra}', [CompraController::class, 'update'])->name('compra.update');
    Route::delete('{codCompra}', [CompraController::class, 'destroy'])->name('compra.destroy');
});

// Rutas para gestionar ventas
Route::prefix('ventas')->group(function () {
    Route::get('', [VentaController::class, 'index'])->name('venta.index');
    Route::get('crear', [VentaController::class, 'create'])->name('venta.create');

    Route::post('store', [VentaController::class, 'store'])->name('venta.store');
    Route::get('{codVenta}', [VentaController::class, 'show'])->name('venta.show');
    Route::post('anular/{codVenta}', [VentaController::class, 'anularVenta'])->name('venta.anular');
});


Route::get('/venta/index', [VentaClienteController::class, 'index'])->name('ventaCliente.index');
Route::get('/api/categorias/{codCategoria}/productos', [VentaClienteController::class, 'getProductos'])->name('venta.getProductos');
Route::get('/api/venta/productos', [VentaClienteController::class, 'obtenerProductos'])->name('venta.obtenerProductos');
Route::get('/comprar/{idsYCantidades}', [VentaClienteController::class, 'mostrarDetalles'])->name('comprar.detalle');
Route::post('/venta/create', [VentaClienteController::class, 'store']);



Route::get('/search', [SearchController::class, 'search'])->name('search');



Route::get('/reporte/index', [ReporteController::class, 'index'])->name('reporte.index');
Route::get('/reporte/generar', [ReporteController::class, 'generarReporte'])->name('reporte.generar');

Route::get('/reporte/index1', [CompraReportController::class, 'index1'])->name('reporte.index1');
Route::get('/reporte/generar1', [CompraReportController::class, 'generarreportecompra'])->name('reporte.generar1');




Route::get('/reporte/index2', [VentaReportController::class, 'index2'])->name('reporte.index2');
Route::get('/reporte/generar2', [VentaReportController::class, 'generarreporteventa'])->name('reporte.generar2');
 });