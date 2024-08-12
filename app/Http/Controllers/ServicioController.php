<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Horario;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    /**
     * Muestra una lista de servicios.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $criterio = $request->input('criterio', 'nombre'); 
        $buscar = $request->input('buscar', '');


        $camposValidos = ['nombre', 'descripcion', 'capacidad', 'codHorarioF'];
        if (!in_array($criterio, $camposValidos)) {
            $criterio = 'nombre';
        }

        $query = Servicio::query();

        if ($buscar !== '') {
            $query->where($criterio, 'like', '%' . $buscar . '%');
        }

        $servicios = $query->paginate(5);
        $horarios = Horario::all();

        return view('GestionarServicio.index', compact('servicios', 'horarios'));
    }

    /**
     * Muestra el formulario para crear un nuevo servicio.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $horarios = Horario::all();
        return view('GestionarServicio.create', compact('horarios'));
    }

    /**
     * Almacena un nuevo servicio en la base de datos.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Crear el servicio sin validaciones
        Servicio::create($request->only(['nombre', 'descripcion', 'capacidad', 'codHorarioF']));

        return redirect()->route('servicio.index')->with('success', 'Servicio creado correctamente');
    }

    /**
     * Muestra el formulario para editar el servicio especificado.
     *
     * @param int $codServicio
     * @return \Illuminate\View\View
     */
    public function edit($codServicio)
    {
        $servicio = Servicio::findOrFail($codServicio);
        $horarios = Horario::all();
        return view('GestionarServicio.edit', compact('servicio', 'horarios'));
    }

    /**
     * Actualiza el servicio especificado en la base de datos.
     *
     * @param Request $request
     * @param int $codServicio
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $codServicio)
    {
        $servicio = Servicio::findOrFail($codServicio);
        $servicio->update($request->only(['nombre', 'descripcion', 'capacidad', 'codHorarioF']));

        return redirect()->route('servicio.index')->with('success', 'Servicio actualizado correctamente');
    }

    /**
     * Elimina el servicio especificado de la base de datos.
     *
     * @param int $codServicio
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($codServicio)
    {
        $servicio = Servicio::findOrFail($codServicio);
        $servicio->delete();

        return redirect()->route('servicio.index')->with('success', 'Servicio eliminado correctamente');
    }
}
