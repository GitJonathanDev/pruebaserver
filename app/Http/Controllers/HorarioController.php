<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function index(Request $request)
    {
        $criterio = $request->criterio;
        $buscar = $request->buscar;

        $query = Horario::query();

        if ($buscar != '') {
            $query->where($criterio, 'like', '%' . $buscar . '%');
        }

        $horarios = $query->paginate(5);

        return view('GestionarHorario.index', compact('horarios'));
    }

    public function create()
    {
        return view('GestionarHorario.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'horaInicio' => 'required|date_format:H:i',
            'horaFin' => 'required|date_format:H:i|after:horaInicio',
        ]);

        $horario = new Horario();
        $horario->horaInicio = $request->horaInicio;
        $horario->horaFin = $request->horaFin;
        $horario->save();

        return redirect()->route('horario.index')->with('success', 'Horario creado correctamente');
    }

    public function edit($codHorario)
    {
        $horario = Horario::findOrFail($codHorario);
        return view('GestionarHorario.edit', compact('horario')); 
    }

    public function update(Request $request, $codHorario)
    {
        $request->validate([
            'horaInicio' => 'required|date_format:H:i',
            'horaFin' => 'required|date_format:H:i|after:horaInicio',
        ]);

        $horario = Horario::findOrFail($codHorario);
        $horario->horaInicio = $request->horaInicio;
        $horario->horaFin = $request->horaFin;
        $horario->save();

        return redirect()->route('horario.index')->with('success', 'Horario actualizado correctamente');
    }

    public function destroy($codHorario)
    {
        $horario = Horario::findOrFail($codHorario);
        $horario->delete();

        return redirect()->route('horario.index')->with('success', 'Horario eliminado correctamente');
    }
}
