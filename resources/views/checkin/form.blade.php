@extends('layouts.public')

@section('title', 'Check-in de estadía')
@section('header_title', 'Check-in de estadía')
@section('header_subtitle', 'Completá tus datos y los de tus acompañantes para registrar el ingreso.')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Revisá los datos:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('checkin.store') }}" method="POST">
    @csrf

    {{-- DATOS DEL INQUILINO --}}
    <div class="mb-3">
        <div class="section-title">Datos del titular de la reserva</div>
        <p class="small-muted">Estos datos se usarán para el registro principal de la estadía.</p>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Nombre</label>
                <input type="text" name="nombre"
                       value="{{ old('nombre') }}"
                       class="form-control form-control-sm" required>
            </div>
            <div class="form-group col-md-6">
                <label>Apellido</label>
                <input type="text" name="apellido"
                       value="{{ old('apellido') }}"
                       class="form-control form-control-sm">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-4">
                <label>DNI / Documento</label>
                <input type="text" name="dni"
                       value="{{ old('dni') }}"
                       class="form-control form-control-sm">
            </div>
            <div class="form-group col-md-4">
                <label>Teléfono</label>
                <input type="text" name="telefono"
                       value="{{ old('telefono') }}"
                       class="form-control form-control-sm">
            </div>
            <div class="form-group col-md-4">
                <label>Email</label>
                <input type="email" name="email"
                       value="{{ old('email') }}"
                       class="form-control form-control-sm">
            </div>
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <textarea name="direccion" rows="2"
                      class="form-control form-control-sm">{{ old('direccion') }}</textarea>
        </div>
    </div>

    <hr>

    {{-- ACOMPAÑANTES --}}
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <div class="section-title mb-0">Acompañantes</div>
                <p class="small-muted mb-1">Podés agregar a las personas que se alojan con vos.</p>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" id="btn-agregar-acompanante">
                + Agregar acompañante
            </button>
        </div>

        <div id="contenedor-acompanantes" class="mt-2">

            <div class="form-row fila-acompanante mb-2">
                <div class="form-group col-md-5">
                    <label>Nombre y apellido</label>
                    <input type="text" name="acompanantes_nombre[]"
                           class="form-control form-control-sm">
                </div>
                <div class="form-group col-md-3">
                    <label>DNI</label>
                    <input type="text" name="acompanantes_dni[]"
                           class="form-control form-control-sm">
                </div>
                <div class="form-group col-md-3">
                    <label>Parentesco</label>
                    <input type="text" name="acompanantes_parentesco[]"
                           class="form-control form-control-sm"
                           placeholder="Ej: hijo, pareja, amigo">
                </div>
                <div class="form-group col-md-1 d-flex align-items-end">
                    <button type="button"
                            class="btn btn-outline-danger btn-sm btn-remove-acompanante">
                        &times;
                    </button>
                </div>
            </div>

        </div>
    </div>

    <hr>

    {{-- ESTADÍA --}}
    <div class="mb-2">
        <div class="section-title">Datos de la estadía</div>
        <p class="small-muted">Indicá el período de alojamiento y el motivo del viaje.</p>

        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Fecha de ingreso</label>
                <input type="date" name="fecha_ingreso"
                       value="{{ old('fecha_ingreso', date('Y-m-d')) }}"
                       class="form-control form-control-sm" required>
            </div>
            <div class="form-group col-md-4">
                <label>Fecha de egreso</label>
                <input type="date" name="fecha_egreso"
                       value="{{ old('fecha_egreso') }}"
                       class="form-control form-control-sm" required>
            </div>
            <div class="form-group col-md-4">
                <label>Tipo de viaje</label>
                <select name="tipo_viaje" class="form-control form-control-sm" required>
                    @php $tipoViaje = old('tipo_viaje'); @endphp
                    <option value="">Seleccione...</option>
                    <option value="turismo"  {{ $tipoViaje=='turismo'?'selected':'' }}>Turismo</option>
                    <option value="trabajo"  {{ $tipoViaje=='trabajo'?'selected':'' }}>Trabajo</option>
                    <option value="salud"    {{ $tipoViaje=='salud'?'selected':'' }}>Salud</option>
                    <option value="estudios" {{ $tipoViaje=='estudios'?'selected':'' }}>Estudios</option>
                    <option value="otro"     {{ $tipoViaje=='otro'?'selected':'' }}>Otro</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Observaciones (opcional)</label>
            <textarea name="observaciones" rows="2"
                      class="form-control form-control-sm"
                      placeholder="Ej: horario estimado de llegada, requerimientos especiales...">{{ old('observaciones') }}</textarea>
        </div>
    </div>

    <div class="text-right mt-3">
        <small class="d-block text-muted mb-2">
            Al enviar el formulario confirmás que los datos son correctos.
        </small>
        <button type="submit" class="btn btn-primary">
            Enviar Check-in
        </button>
    </div>

</form>

@endsection

@section('footer')
    <small class="text-muted">
        Si ya completaste el formulario, podés cerrar esta ventana.
    </small>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const contenedor = document.getElementById('contenedor-acompanantes');
        const btnAgregar = document.getElementById('btn-agregar-acompanante');

        btnAgregar.addEventListener('click', function () {
            const filas = contenedor.getElementsByClassName('fila-acompanante');
            if (filas.length === 0) return;

            const ultima = filas[filas.length - 1];
            const nueva  = ultima.cloneNode(true);

            nueva.querySelectorAll('input').forEach(input => input.value = '');

            contenedor.appendChild(nueva);
        });

        contenedor.addEventListener('click', function (e) {
            if (e.target.closest('.btn-remove-acompanante')) {
                const filas = contenedor.getElementsByClassName('fila-acompanante');
                if (filas.length <= 1) {
                    filas[0].querySelectorAll('input').forEach(i => i.value = '');
                    return;
                }
                e.target.closest('.fila-acompanante').remove();
            }
        });
    });
</script>
@endsection
