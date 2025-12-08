@extends('adminlte::page')

@section('title', 'Movimientos de Caja')

@section('content_header')
    <h1>Movimientos de Caja</h1>
@stop

@section('content')

<a href="{{ route('movimientos.create') }}" class="btn btn-success btn-sm mb-3">
    <i class="fa fa-plus"></i> Nuevo Movimiento
</a>

@php
    $ingresos = $movimientos->where('tipo','ingreso')->sum('monto');
    $egresos  = $movimientos->where('tipo','egreso')->sum('monto');
    $saldo    = $ingresos - $egresos;
@endphp

<div class="mb-3">
    <strong>Saldo actual:</strong>
    <span class="badge badge-info">
        ${{ number_format($saldo, 2, ',', '.') }}
    </span>
</div>

<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Concepto</th>
                    <th>Monto</th>
                    <th>Estadía</th>
                    <th>Medio Pago</th>
                    <th>Obs.</th>
                    <th width="120">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movimientos as $m)
                    <tr>
                        <td>{{ optional($m->fecha)->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($m->tipo == 'ingreso')
                                <span class="badge badge-success">Ingreso</span>
                            @else
                                <span class="badge badge-danger">Egreso</span>
                            @endif
                        </td>
                        <td>{{ $m->concepto }}</td>
                        <td>${{ number_format($m->monto, 2, ',', '.') }}</td>
                        <td>
                            @if($m->estadia)
                                #{{ $m->estadia->id }} - {{ $m->estadia->inquilino->nombre_completo ?? '' }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $m->medio_pago }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($m->observaciones, 30) }}</td>
                        <td>
                            <a href="{{ route('movimientos.edit', $m) }}"
                               class="btn btn-warning btn-xs">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('movimientos.destroy', $m) }}"
                                  method="POST" style="display:inline-block;"
                                  onsubmit="return confirm('¿Eliminar movimiento?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-xs">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if($movimientos->isEmpty())
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            No hay movimientos registrados.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@stop
