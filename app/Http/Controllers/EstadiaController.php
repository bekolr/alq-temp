<?php

namespace App\Http\Controllers;

use App\Models\Estadia;
use App\Models\Inquilino;
use App\Models\Departamento;
use Illuminate\Http\Request;


use App\Models\Movimiento;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EstadiaController extends Controller
{
    public function index()
    {
        $estadias = Estadia::with(['inquilino', 'departamento'])
            ->orderByDesc('fecha_ingreso')
            ->get();

        return view('estadias.index', compact('estadias'));
    }

    public function create()
    {
        $inquilinos    = Inquilino::orderBy('nombre')->get();
        $departamentos = Departamento::orderBy('nombre')->get();

        return view('estadias.create', compact('inquilinos', 'departamentos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'inquilino_id'    => 'required|exists:inquilinos,id',
            'departamento_id' => 'required|exists:departamentos,id',
            'fecha_ingreso'   => 'required|date',
            'fecha_egreso'    => 'required|date|after_or_equal:fecha_ingreso',
           
            'observaciones'   => 'nullable|string',
        ]);

        $data['estado'] = 'checkin';

        Estadia::create($data);

        return redirect()->route('estadias.index')
            ->with('success', 'Estadía creada y check-in realizado.');
    }

    public function show(Estadia $estadia)
    {
        return redirect()->route('estadias.edit', $estadia);
    }

    public function edit(Estadia $estadia)
    {
        $inquilinos    = Inquilino::orderBy('nombre')->get();
        $departamentos = Departamento::orderBy('nombre')->get();

        return view('estadias.edit', compact('estadia', 'inquilinos', 'departamentos'));
    }

    public function update(Request $request, Estadia $estadia)
    {
       
        $data = $request->validate([
            'inquilino_id'    => 'required|exists:inquilinos,id',
            'departamento_id' => 'required|exists:departamentos,id',
            'fecha_ingreso'   => 'required|date',
            'fecha_egreso'    => 'required|date|after_or_equal:fecha_ingreso',
            'monto_total'     => 'required|numeric|min:0',
            'estado_pago'    => 'required|string',
            'observaciones'   => 'nullable|string',

        ]);

        $estadia->update($data);

        return redirect()->route('estadias.index')
            ->with('success', 'Estadía actualizada correctamente.');
    }

    public function destroy(Estadia $estadia)
    {
        $estadia->delete();

        return redirect()->route('estadias.index')
            ->with('success', 'Estadía eliminada correctamente.');
    }

    public function checkout(Estadia $estadia)
    {
        $estadia->update(['estado' => 'checkout']);

        return redirect()->route('estadias.index')
            ->with('success', 'Check-out realizado correctamente.');
    }


// ...

public function calendario()
{
    // Solo devolvemos la vista, los eventos se cargan por AJAX
    return view('estadias.calendario');
}

public function calendarioEvents(Request $request)
{
    // FullCalendar suele mandar 'start' y 'end' en ISO, pero si no vienen, usamos un rango amplio
    $start = $request->query('start', now()->startOfMonth()->subMonth()->toDateString());
    $end   = $request->query('end',   now()->endOfMonth()->addMonth()->toDateString());

    $estadias = Estadia::with(['inquilino', 'departamento'])
        ->whereDate('fecha_ingreso', '<=', $end)
        ->whereDate('fecha_egreso', '>=', $start)
        ->get();

    $events = [];

    foreach ($estadias as $e) {
        // Definir color según estado de pago
        $estadoPago = $e->estado_pago ?? 'pendiente';
        switch ($estadoPago) {
            case 'pagado':
                $color = '#28a745'; // verde
                break;
            default:
                $color = '#ffc107'; // amarillo/pending
                break;
        }

        // Texto del título
        $titulo = ($e->departamento->nombre ?? 'Depto')
            . ' - ' . ($e->inquilino->nombre_completo ?? 'Inquilino')
            . ' (' . strtoupper($estadoPago) . ')';

        // FullCalendar toma end como EXCLUSIVO, sumo 1 día para cubrir la última noche
        $startDate = $e->fecha_ingreso instanceof Carbon
            ? $e->fecha_ingreso->toDateString()
            : Carbon::parse($e->fecha_ingreso)->toDateString();

        $endDate = $e->fecha_egreso instanceof Carbon
            ? $e->fecha_egreso->copy()->addDay()->toDateString()
            : Carbon::parse($e->fecha_egreso)->addDay()->toDateString();

        $events[] = [
            'id'    => $e->id,
            'title' => $titulo,
            'start' => $startDate,
            'end'   => $endDate,
            'backgroundColor' => $color,
            'borderColor'     => $color,
            'textColor'       => '#fff',
            'extendedProps'   => [
                'estado'       => $e->estado,
                'estado_pago'  => $estadoPago,
                'inquilino'    => $e->inquilino->nombre_completo ?? '',
                'departamento' => $e->departamento->nombre ?? '',
            ],
            'url' => route('estadias.edit', $e), // click abre edición
        ];
    }

    return response()->json($events);
}


public function pagar(Request $request, Estadia $estadia)
{
    $data = $request->validate([
        'monto_total'   => 'required|numeric|min:0',
        'medio_pago'    => 'nullable|string|max:100',
        'observaciones' => 'nullable|string',
    ]);

    // Evitar duplicar ingreso si ya estaba pagado
    if ($estadia->estado_pago === 'pagado') {
        return redirect()->back()->with('info', 'La estadía ya estaba marcada como pagada.');
    }

    DB::transaction(function () use ($data, $estadia) {

        // 1) Actualizar la estadía
        $estadia->update([
            'monto_total' => $data['monto_total'],
            'estado_pago' => 'pagado',
        ]);

        // 2) Crear movimiento de caja (ingreso)
        Movimiento::create([
            'tipo_movimiento'        => 'INGRESO',
            'concepto_id'    => 1,
            'fecha'       => now(),
            'monto'       => $data['monto_total'],
            
            'medio_pago'  => $data['medio_pago'] ?? null,
            'descripcion' => 
                    'Pago estadía #' . $estadia->id . 
                    ' - ' . ($data['observaciones'] ?? ''),
        ]);
    });

    return redirect()->back()->with('success', 'Pago registrado y movimiento de caja generado.');
}

}
