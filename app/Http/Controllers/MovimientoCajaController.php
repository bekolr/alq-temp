<?php

namespace App\Http\Controllers;

use App\Models\MovimientoCaja;
use App\Models\Estadia;
use Illuminate\Http\Request;

class MovimientoCajaController extends Controller
{
    public function index()
    {
        $movimientos = MovimientoCaja::with(['estadia.inquilino'])
            ->orderByDesc('fecha')
            ->get();

        return view('movimientos.index', compact('movimientos'));
    }

    public function create()
    {
        $estadias = Estadia::with('inquilino')->orderByDesc('id')->get();
        return view('movimientos.create', compact('estadias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipo'        => 'required|in:ingreso,egreso',
            'concepto'    => 'required|string|max:255',
            'fecha'       => 'nullable|date',
            'monto'       => 'required|numeric|min:0',
            'estadia_id'  => 'nullable|exists:estadias,id',
            'medio_pago'  => 'nullable|string|max:100',
            'observaciones' => 'nullable|string',
        ]);

        if (empty($data['fecha'])) {
            $data['fecha'] = now();
        }

        MovimientoCaja::create($data);

        return redirect()->route('movimientos.index')
            ->with('success', 'Movimiento de caja registrado.');
    }

    public function show(MovimientoCaja $movimiento)
    {
        return redirect()->route('movimientos.edit', $movimiento);
    }

    public function edit(MovimientoCaja $movimiento)
    {
        $estadias = Estadia::with('inquilino')->orderByDesc('id')->get();
        return view('movimientos.edit', compact('movimiento', 'estadias'));
    }

    public function update(Request $request, MovimientoCaja $movimiento)
    {
        $data = $request->validate([
            'tipo'        => 'required|in:ingreso,egreso',
            'concepto'    => 'required|string|max:255',
            'fecha'       => 'required|date',
            'monto'       => 'required|numeric|min:0',
            'estadia_id'  => 'nullable|exists:estadias,id',
            'medio_pago'  => 'nullable|string|max:100',
            'observaciones' => 'nullable|string',
        ]);

        $movimiento->update($data);

        return redirect()->route('movimientos.index')
            ->with('success', 'Movimiento actualizado correctamente.');
    }

    public function destroy(MovimientoCaja $movimiento)
    {
        $movimiento->delete();

        return redirect()->route('movimientos.index')
            ->with('success', 'Movimiento eliminado correctamente.');
    }
}
