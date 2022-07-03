<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return redirect()->to("/login");
});
//-------------------------------
// Áreas
//-------------------------------


Route::group(
    [
        "middleware" => [
            "auth"
        ]
    ],
    function () {

        # API
        Route::prefix("api")
            ->group(function () {
                // Proveedores
                Route::post("proveedor", "ProveedorController@agregar");
                Route::get("proveedores", "ProveedorController@mostrar");
                Route::get("proveedor/{id}", "ProveedorController@porId");
                Route::get("proveedores/buscar/{busqueda}", "ProveedorController@buscar");
                Route::delete("proveedor/{id}", "ProveedorController@eliminar");
                Route::post("proveedores/eliminar", "ProveedorController@eliminarMuchos");
                Route::put("proveedor", "ProveedorController@guardarCambios")->name("guardarCambiosDeProveedor");

                // Clientes
                Route::post("cliente", "ClienteController@agregar");
                Route::get("clientes", "ClienteController@mostrar");
                Route::get("cliente/{id}", "ClienteController@porId");
                Route::get("clientes/buscar/{busqueda}", "ClienteController@buscar");
                Route::delete("cliente/{id}", "ClienteController@eliminar");
                Route::post("clientes/eliminar", "ClienteController@eliminarMuchos");
                Route::put("cliente", "ClienteController@guardarCambios")->name("guardarCambiosDeCliente");

                // Divisas
                Route::get("divisas", "DivisasController@mostrar");
                Route::get("divisas/buscar/{busqueda}", "DivisasController@buscar");
                Route::delete("divisa/{id}", "DivisasController@eliminar");
                Route::post("divisas/eliminar", "DivisasController@eliminarMuchas");

                // Áreas
                Route::get("areas", "AreasController@mostrar");
                Route::get("areas/buscar/{busqueda}", "AreasController@buscar");
                Route::delete("area/{id}", "AreasController@eliminar");
                Route::post("areas/eliminar", "AreasController@eliminarMuchas");
                // Responsables
                Route::post("/responsable", "ResponsablesController@agregar");
                Route::get("responsables", "ResponsablesController@mostrar");
                Route::get("responsable/{id}", "ResponsablesController@porId");
                Route::get("responsables/buscar/{busqueda}", "ResponsablesController@buscar");
                Route::delete("responsable/{id}", "ResponsablesController@eliminar");
                Route::post("responsables/eliminar", "ResponsablesController@eliminarMuchos");
                Route::put("responsable/", "ResponsablesController@guardarCambios")->name("guardarCambiosDeResponsable");
                // Artículos
                Route::post("/articulo", "ArticulosController@agregar");
                Route::get("/articulos", "ArticulosController@mostrar");
                Route::get("articulo/{id}", "ArticulosController@porId");
                Route::post("articulo/{id}", "ArticulosController@guardarCambios")->name("guardarCambiosDeArticulo");

                Route::get("/articulos/buscar/{busqueda}", "ArticulosController@buscar");
                Route::get("/articulos/inventario/{articulo}/buscar", "ArticulosController@buscarInventario");
                Route::get("/articulos/proveedor/{id}", "ArticulosController@buscarPorProveedor");
                Route::post("/articulos/inventario/{articulo}", "ArticulosController@crearInventario")->name('guardarInventario');
                Route::post("/inventarios/eliminar/{articulo}", "ArticulosController@eliminarInventario")->name('eliminarInventario');

                // Fotos de artículos
                Route::post("eliminar/foto/articulo/", "ArticulosController@eliminarFoto")->name("eliminarFotoDeArticulo");

            });

        # PROVEEDORES
        Route::view("proveedores/agregar", "proveedores/agregar")->name("formularioAgregarProveedor");
        Route::view("proveedores/", "proveedores/mostrar")->name("proveedores");
        Route::view("proveedores/editar/{id}", "proveedores/editar")->name("formularioEditarProveedor");

        # CLIENTES
        Route::view("clientes/agregar", "clientes/agregar")->name("formularioAgregarCliente");
        Route::view("clientes/", "clientes/mostrar")->name("clientes");
        Route::view("clientes/editar/{id}", "clientes/editar")->name("formularioEditarCliente");

        # VISTAS DIVISAS
        Route::view("divisas/agregar", "agregar_divisa")->name("formularioDivisa");
        Route::get("divisas/editar/{id}", "DivisasController@editar")->name("formularioEditarDivisa");
        Route::view("divisas/", "divisas")->name("divisas");
        # Otras cosas
        Route::post("divisas/agregar", "DivisasController@agregar")->name("guardarDivisa");
        Route::put("divisa/", "DivisasController@guardarCambios")->name("guardarCambiosDeDivisa");

        # VISTAS
        Route::view("areas/agregar", "agregar_area")->name("formularioArea");
        Route::get("areas/editar/{id}", "AreasController@editar")->name("formularioEditarArea");
        Route::view("areas/", "areas")->name("areas");
        # Otras cosas
        Route::post("areas/agregar", "AreasController@agregar")->name("guardarArea");
        Route::put("area/", "AreasController@guardarCambios")->name("guardarCambiosDeArea");

        Route::get("foto/articulo/{nombre}", "ArticulosController@foto")->name("fotoDeArticulo");
        Route::get("descargar/foto/articulo/{nombre}", "ArticulosController@descargar")->name("descargarFotoDeArticulo");


        //-------------------------------
        // Responsables
        //-------------------------------
        Route::view("responsables/agregar", "responsables/agregar")->name("formularioAgregarResponsable");
        Route::view("responsables/", "responsables/mostrar")->name("responsables");
        Route::view("responsables/editar/{id}", "responsables/editar")->name("formularioEditarResponsable");
        //-------------------------------
        // Artículos
        //-------------------------------
        Route::view("articulos/agregar", "articulos.agregar")->name("formularioAgregarArticulo");
        Route::view("articulos/", "articulos/mostrar")->name("articulos");
        Route::view("articulos/editar/{id}", "articulos/editar")->name("formularioEditarArticulo");
        Route::get("articulos/fotos/{id}", "ArticulosController@administrarFotos")->name("administrarFotos");
        Route::get("articulos/eliminar/{id}", "ArticulosController@vistaDarDeBaja")->name("vistaDarDeBajaArticulo");
        Route::post("articulos/fotos", "ArticulosController@agregarFotos")->name("agregarFotosDeArticulo");
        Route::post("articulos/eliminar", "ArticulosController@eliminar")->name("eliminarArticulo");


        Route::get("articulos/inventario/{articulo}", "ArticulosController@mostrarInventario")->name("articulos.inventario");
        Route::get("articulos/inventario/{articulo}/agregar", "ArticulosController@agregarInventario")->name("articulos.inventario.agregar");

        Route::get("/inventarios/editar/{inventario}", "ArticulosController@editarInventario")->name('editarInventario');
        Route::post("/inventarios/editar/{inventario}", "ArticulosController@editarInventarioPost")->name('editarInventarioPost');


        # Logout
        Route::get("logout", function () {
            Auth::logout();
            # Intentar redireccionar a una protegida, que a su vez redirecciona al login :)
            return redirect()->route("articulos");
        })->name("logout");
    }
);


Auth::routes(["register" => false]);

Route::get('/home', 'HomeController@index')->name('home');
