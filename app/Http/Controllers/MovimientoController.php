<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movimiento;
use App\Models\Concepto;
use App\Models\TipoMovimiento;

class MovimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $mes        = $request->input('mes', now()->format('m'));
    $anio       = $request->input('anio', now()->format('Y'));
    $tipo       = $request->input('tipo'); // 'ingreso' / 'egreso' / null
    $conceptoId = $request->input('concepto_id');
    $metodo     = $request->input('metodo_pago');

    $tipoFiltro = $tipo ? strtoupper($tipo) : null;

    $base = Movimiento::query()
        ->when($mes,        fn($q) => $q->whereMonth('fecha', (int)$mes))
        ->when($anio,       fn($q) => $q->whereYear('fecha', (int)$anio))
        ->when($tipoFiltro, fn($q) => $q->where('tipo_movimiento', $tipoFiltro))
        ->when($conceptoId, fn($q) => $q->where('concepto_id', $conceptoId))
        ->when($metodo,     fn($q) => $q->where('metodo_pago', $metodo));

    // listado
    $movimientos = (clone $base)
        ->with('concepto')
        ->orderBy('fecha', 'desc')
        ->orderBy('id', 'desc')
        ->paginate(25)
        ->withQueryString();

    // totales
    $totales = (clone $base)
        ->selectRaw("
            SUM(CASE WHEN tipo_movimiento = 'INGRESO' THEN monto ELSE 0 END) AS total_ingresos,
            SUM(CASE WHEN tipo_movimiento = 'EGRESO'  THEN monto ELSE 0 END) AS total_egresos
        ")
        ->first();

    $balance = ($totales->total_ingresos ?? 0) - ($totales->total_egresos ?? 0);

    // conceptos
    $conceptos = Concepto::orderBy('tipo_movimiento')
        ->orderBy('nombre')
        ->get()
        ->groupBy('tipo_movimiento');

    // 📊 DATOS PARA EL GRÁFICO (por día del mes seleccionado)
    $serie = (clone $base)
        ->selectRaw("
            DATE(fecha) as dia,
            SUM(CASE WHEN tipo_movimiento = 'INGRESO' THEN monto ELSE 0 END) AS total_ingresos,
            SUM(CASE WHEN tipo_movimiento = 'EGRESO'  THEN monto ELSE 0 END) AS total_egresos
        ")
        ->groupBy('dia')
        ->orderBy('dia')
        ->get();

    $labels       = $serie->pluck('dia')->map(
        fn($d) => \Illuminate\Support\Carbon::parse($d)->format('d/m')
    )->values();

    $ingresosData = $serie->pluck('total_ingresos')->map(fn($v) => (float)$v)->values();
    $egresosData  = $serie->pluck('total_egresos')->map(fn($v) => (float)$v)->values();

    return view('movimientos.index', compact(
        'movimientos','totales','balance','conceptos',
        'mes','anio','tipo','conceptoId','metodo',
        'labels','ingresosData','egresosData' // 👈 NUEVO
    ));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

         $conceptos = Concepto::orderBy('tipo_movimiento')->orderBy('nombre')->get()->groupBy('tipo');

        $hoy = now()->toDateString();
       

        // Por si querés un combo de métodos predefinidos
        $metodos = ['efectivo','transferencia','tarjeta','mercado_pago','cheque','otro'];

        return view('movimientos.create', compact('conceptos','hoy','metodos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

         $data = $request->validate([
            'fecha'           => ['required','date'],
            'tipo_movimiento'            => ['required','in:INGRESO,EGRESO'],
            'concepto_id'     => ['required','exists:conceptos,id'],
            'monto'           => ['required','numeric','min:0.01'],
            'metodo_pago'     => ['nullable','string','max:100'],
            'descripcion'     => ['nullable','string'],
            // referencia polimórfica opcional
            'referencia_type' => ['nullable','string','max:255'],
            'referencia_id'   => ['nullable','integer'],
        ]);

        $data['creado_por'] = auth()->id();

        // Armar campos morph si vienen
        if ($request->filled('referencia_type') && $request->filled('referencia_id')) {
            $data['referencia_type'] = $request->referencia_type;
            $data['referencia_id']   = $request->referencia_id;
        } else {
            $data['referencia_type'] = null;
            $data['referencia_id']   = null;
        }

        Movimiento::create($data);

        return redirect()->route('movimientos.index')->with('success','Movimiento registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //

         $conceptos = Concepto::orderBy('tipo')->orderBy('nombre')->get()->groupBy('tipo');
        $metodos = ['efectivo','transferencia','tarjeta','mercado_pago','cheque','otro'];
        return view('movimientos.edit', compact('movimiento','conceptos','metodos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
         $data = $request->validate([
            'fecha'           => ['required','date'],
            'tipo'            => ['required','in:ingreso,egreso'],
            'concepto_id'     => ['required','exists:conceptos,id'],
            'monto'           => ['required','numeric','min:0.01'],
            'metodo_pago'     => ['nullable','string','max:100'],
            'descripcion'     => ['nullable','string'],
            'referencia_type' => ['nullable','string','max:255'],
            'referencia_id'   => ['nullable','integer'],
        ]);

        if (!($request->filled('referencia_type') && $request->filled('referencia_id'))) {
            $data['referencia_type'] = null;
            $data['referencia_id']   = null;
        }

        $movimiento->update($data);

        return redirect()->route('movimientos.index')->with('success','Movimiento actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
          $movimiento->delete();
        return back()->with('success','Movimiento eliminado.');
    }
}
