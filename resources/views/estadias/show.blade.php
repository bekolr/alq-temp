@extends('adminlte::page')

@section('title', 'Detalle de Estadía')

@section('content_header')
  <div class="d-flex justify-content-between align-items-center">
    <h1>Detalle de Estadía #{{ $estadia->id }}</h1>
    <a href="{{ Route('estadias.index') }}" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i> Volver
    </a>
  </div>
@stop

@section('content')

  {{-- Datos de la estadía --}}
  <div class="card">
    <div class="card-header">
      <h3 class="card-title"><i class="fas fa-info-circle"></i> Datos</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-4">
          <strong>Departamento:</strong><br>
          {{ optional($estadia->departamento)->nombre ?? '-' }}
        </div>
        <div class="col-md-4">
          <strong>Titular:</strong><br>
          {{ optional($estadia->titular)->nombre ?? '-' }}
        </div>
        <div class="col-md-4">
          <strong>Estado:</strong><br>
          <span class="badge badge-{{ $estadia->estado == 'activa' ? 'success' : 'secondary' }}">
            {{ $estadia->estado ?? '-' }}
          </span>
        </div>

        <div class="col-md-4 mt-3">
          <strong>Ingreso:</strong><br>
          {{ $estadia->fecha_ingreso ?? '-' }}
        </div>
        <div class="col-md-4 mt-3">
          <strong>Egreso:</strong><br>
          {{ $estadia->fecha_egreso ?? '-' }}
        </div>
        <div class="col-md-4 mt-3">
          <strong>Monto:</strong><br>
          {{ isset($estadia->monto) ? '$ '.number_format($estadia->monto, 2, ',', '.') : '-' }}
        </div>

        <div class="col-12 mt-3">
          <strong>Observaciones:</strong><br>
          {{ $estadia->observaciones ?? '-' }}
        </div>
      </div>
    </div>
  </div>

  {{-- Acompañantes --}}
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h3 class="card-title"><i class="fas fa-users"></i> Acompañantes</h3>

      {{-- si tenés alta de acompañantes --}}
    </div>

    <div class="card-body">
      <table id="tablaAcompanantes" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Apellido y Nombre</th>
            <th>DNI</th>
            <th>Edad</th>
            <th>Observación</th>
            <th style="width:140px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($estadia->acompanantes as $a)
            <tr>
              <td>{{ $a->id }}</td>
              <td>{{ $a->nombre ?? '-' }}</td>
              <td>{{ $a->dni ?? '-' }}</td>
              <td>{{ $a->edad ?? '-' }}</td>
              <td>{{ $a->observacion ?? '-' }}</td>
              <td>
                <a class="btn btn-sm btn-warning"
                   href="">
                  <i class="fas fa-edit"></i>
                </a>

                <form action=""
                      method="POST" class="d-inline"
                      onsubmit="return confirm('¿Eliminar acompañante?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      @if($estadia->acompanantes->isEmpty())
        <div class="text-muted">No hay acompañantes cargados.</div>
      @endif
    </div>
  </div>

@stop

@section('js')
<script>
  $(function () {
    $('#tablaAcompanantes').DataTable({
      responsive: true,
      autoWidth: false,
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
      }
    });
  });
</script>
@stop
