@extends('adminlte::page')

@section('title', 'Editar Estadía')

@section('content_header')
    <h1>Editar Estadía #{{ $estadia->id }}</h1>
@stop

@section('content')

<div class="row">
    <div class="col-lg-10">

        <form action="{{ route('estadias.update', $estadia) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- DATOS PRINCIPALES --}}
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Datos generales de la estadía</h3>
                </div>
                <div class="card-body">

                    <div class="form-row">
                        {{-- INQUILINO --}}
                        <div class="form-group col-md-6">
                            <label>Inquilino</label>
                            <select name="inquilino_id"
                                    class="form-control form-control-sm" required>
                                <option value="">Seleccione...</option>
                                @foreach($inquilinos as $inq)
                                    <option value="{{ $inq->id }}"
                                        {{ old('inquilino_id', $estadia->inquilino_id) == $inq->id ? 'selected' : '' }}>
                                        {{ $inq->nombre_completo }} ({{ $inq->dni }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- DEPARTAMENTO --}}
                        <div class="form-group col-md-6">
                            <label>Departamento</label>
                            <select name="departamento_id"
                                    class="form-control form-control-sm" required>
                                <option value="">Seleccione...</option>
                                @foreach($departamentos as $dpto)
                                    <option value="{{ $dpto->id }}"
                                        {{ old('departamento_id', $estadia->departamento_id) == $dpto->id ? 'selected' : '' }}>
                                        {{ $dpto->nombre }} - {{ $dpto->direccion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        {{-- FECHA INGRESO --}}
                        <div class="form-group col-md-3">
                            <label>Fecha ingreso</label>
                            <input type="date" name="fecha_ingreso"
                                   value="{{ old('fecha_ingreso', optional($estadia->fecha_ingreso)->format('Y-m-d')) }}"
                                   class="form-control form-control-sm" required>
                        </div>

                        {{-- FECHA EGRESO --}}
                        <div class="form-group col-md-3">
                            <label>Fecha egreso</label>
                            <input type="date" name="fecha_egreso"
                                   value="{{ old('fecha_egreso', optional($estadia->fecha_egreso)->format('Y-m-d')) }}"
                                   class="form-control form-control-sm" required>
                        </div>

                        {{-- TIPO VIAJE --}}
                        <div class="form-group col-md-3">
                            <label>Tipo de viaje</label>
                            <select name="tipo_viaje"
                                    class="form-control form-control-sm" required>
                                <option value="">Seleccione...</option>
                                @php
                                    $tipoViaje = old('tipo_viaje', $estadia->tipo_viaje);
                                @endphp
                                <option value="turismo"  {{ $tipoViaje == 'turismo'  ? 'selected' : '' }}>Turismo</option>
                                <option value="trabajo"  {{ $tipoViaje == 'trabajo'  ? 'selected' : '' }}>Trabajo</option>
                                <option value="salud"    {{ $tipoViaje == 'salud'    ? 'selected' : '' }}>Salud</option>
                                <option value="estudios" {{ $tipoViaje == 'estudios' ? 'selected' : '' }}>Estudios</option>
                                <option value="otro"     {{ $tipoViaje == 'otro'     ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>

                        {{-- ESTADO (RESERVADA / CHECKIN / CHECKOUT) --}}
                        <div class="form-group col-md-3">
                            <label>Estado de la estadía</label>
                            <select name="estado"
                                    class="form-control form-control-sm" required>
                                @php
                                    $estado = old('estado', $estadia->estado);
                                @endphp
                                <option value="reservada" {{ $estado == 'reservada' ? 'selected' : '' }}>Reservada</option>
                                <option value="checkin"   {{ $estado == 'checkin'   ? 'selected' : '' }}>Check-in</option>
                                <option value="checkout"  {{ $estado == 'checkout'  ? 'selected' : '' }}>Check-out</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>

            {{-- PAGO --}}
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Pago de la estadía</h3>
                </div>
                <div class="card-body">

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Monto total de la estadía</label>
                            <input type="number" step="0.01" name="monto_total"
                                   value="{{ old('monto_total', $estadia->monto_total) }}"
                                   class="form-control form-control-sm" required>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Estado del pago</label>
                            @php
                                $estadoPago = old('estado_pago', $estadia->estado_pago);
                            @endphp
                            <select name="estado_pago"
                                    class="form-control form-control-sm" required>
                                <option value="pendiente" {{ $estadoPago == 'pendiente' ? 'selected' : '' }}>
                                    Pendiente
                                </option>
                                <option value="pagado" {{ $estadoPago == 'pagado' ? 'selected' : '' }}>
                                    Pagado
                                </option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>

            {{-- OBSERVACIONES --}}
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Observaciones</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <textarea name="observaciones" rows="3"
                                  class="form-control form-control-sm">{{ old('observaciones', $estadia->observaciones) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- BOTONES --}}
            <div class="mb-3 text-right">
                <a href="{{ route('estadias.index') }}" class="btn btn-secondary btn-sm">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary btn-sm">
                    Guardar cambios
                </button>
            </div>

        </form>
    </div>
</div>

@stop
