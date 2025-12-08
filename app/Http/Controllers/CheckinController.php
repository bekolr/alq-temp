<?php

namespace App\Http\Controllers;

use App\Models\Inquilino;
use App\Models\Departamento;
use App\Models\Estadia;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    // Mostrar formulario público
    public function create()
    {
        $departamentos = Departamento::orderBy('nombre')->get();

        return view('checkin.form', compact('departamentos'));
    }

public function store(Request $request)
{
    $data = $request->validate([
        // Datos del inquilino
        'nombre'    => 'required|string|max:255',
        'apellido'  => 'nullable|string|max:255',
        'dni'       => 'nullable|string|max:20',
        'telefono'  => 'nullable|string|max:30',
        'email'     => 'nullable|email',
        'direccion' => 'nullable|string',

        // Datos de la estadía
        'fecha_ingreso' => 'required|date',
        'fecha_egreso'  => 'required|date|after_or_equal:fecha_ingreso',
        'tipo_viaje'    => 'required|string|max:50',
        'observaciones' => 'nullable|string',

        // Acompañantes (arrays)
        'acompanantes_nombre'        => 'array',
        'acompanantes_nombre.*'      => 'nullable|string|max:255',
        'acompanantes_dni'           => 'array',
        'acompanantes_dni.*'         => 'nullable|string|max:20',
        'acompanantes_parentesco'    => 'array',
        'acompanantes_parentesco.*'  => 'nullable|string|max:50',
    ]);

    // 1) Crear inquilino
    $inquilino = Inquilino::create([
        'nombre'    => $data['nombre'],
        'apellido'  => $data['apellido']  ?? null,
        'dni'       => $data['dni']       ?? null,
        'telefono'  => $data['telefono']  ?? null,
        'email'     => $data['email']     ?? null,
        'direccion' => $data['direccion'] ?? null,
    ]);

    // 2) Crear estadía
    $estadia = Estadia::create([
        'inquilino_id'  => $inquilino->id,
        'fecha_ingreso' => $data['fecha_ingreso'],
        'fecha_egreso'  => $data['fecha_egreso'],
        'estado'        => 'checkin',
        'tipo_viaje'    => $data['tipo_viaje'],
        'observaciones' => $data['observaciones'] ?? null,
        'departamento_id' => 1,

        // Si en tu tabla 'estadias' tenés estos campos NO nulos,
        // agregalos también acá y al form:
        // 
        // 'monto_total'     => $data['monto_total'],
    ]);

    // 3) Crear acompañantes relacionados (cero, uno o varios)
    $nombres     = $request->input('acompanantes_nombre', []);
    $dnis        = $request->input('acompanantes_dni', []);
    $parentescos = $request->input('acompanantes_parentesco', []);

    foreach ($nombres as $idx => $nombre) {
        $nombre = trim($nombre ?? '');

        // Si la fila está totalmente vacía, la ignoro
        $dni        = $dnis[$idx]        ?? null;
        $parentesco = $parentescos[$idx] ?? null;

        if ($nombre === '' && ($dni === null || $dni === '')) {
            continue;
        }

        $estadia->acompanantes()->create([
            'nombre'     => $nombre,
            'dni'        => $dni,
            'parentesco' => $parentesco,
        ]);
    }

    return redirect()->route('checkin.ok')
        ->with('success', 'Check-in registrado correctamente.');
}


    // Pantalla de gracias / confirmación
    public function ok()
    {
        return view('checkin.ok');
    }
}
