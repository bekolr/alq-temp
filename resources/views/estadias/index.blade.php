@extends('adminlte::page')

@section('title', 'Estadías')

@section('content_header')
    <h1>Estadías / Check-in</h1>
@stop

@section('content')

<a href="{{ route('estadias.create') }}" class="btn btn-primary btn-sm mb-3">
    <i class="fa fa-plus"></i> Nuevo Check-in
</a>

<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Inquilino</th>
                    <th>Departamento</th>
                    <th>Ingreso</th>
                    <th>Egreso</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Estado Pago</th>
                    <th width="150">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estadias as $e)
                    <tr>
                        <td>{{ $e->id }}</td>
                        <td>{{ $e->inquilino->nombre_completo ?? '-' }}</td>
                        <td>{{ $e->departamento->nombre ?? '-' }}</td>
                        <td>{{ optional($e->fecha_ingreso)->format('d/m/Y') }}</td>
                        <td>{{ optional($e->fecha_egreso)->format('d/m/Y') }}</td>

                        <td>${{ number_format($e->monto_total, 2, ',', '.') }}</td>
                        <td>
                            @if($e->estado == 'reservada')
                                <span class="badge badge-secondary">Reservada</span>
                            @elseif($e->estado == 'checkin')
                                <span class="badge badge-success">Check-in</span>
                            @else
                                <span class="badge badge-info">Check-out</span>
                            @endif
                        </td>
                           <td>
                            @if($e->estado_pago== 'pendiente')
                                <span class="badge badge-warning">Pendiente</span>
                            @else($e->estado == 'pagada')
                                <span class="badge badge-success">Pagada</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('estadias.edit', $e) }}" class="btn btn-warning btn-xs">
                                <i class="fa fa-edit"></i>
                            </a>
                              {{-- BOTÓN PAGAR --}}
                                @if($e->estado_pago == 'pendiente')
                                    <button class="btn btn-primary btn-xs"
                                            data-toggle="modal"
                                            data-target="#modalPagar"
                                            data-id="{{ $e->id }}"
                                            data-monto="{{ $e->monto_total }}">
                                        <i class="fa fa-money-bill-wave"></i>
                                    </button>
                                @endif

                            @if($e->estado != 'checkout')
                                <form action="{{ route('estadias.checkout', $e) }}"
                                      method="POST" style="display:inline-block;"
                                      onsubmit="return confirm('¿Confirmar check-out?')">
                                    @csrf
                                    <button class="btn btn-success btn-xs">
                                        <i class="fa fa-door-open"></i>
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('estadias.destroy', $e) }}"
                                  method="POST" style="display:inline-block;"
                                  onsubmit="return confirm('¿Eliminar estadía?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-xs">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>

                        </td>
                      
                    </tr>
                @endforeach
                @if($estadias->isEmpty())
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            No hay estadías cargadas.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL PAGAR --}}
<div class="modal fade" id="modalPagar" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">

      <form method="POST" id="formPagar">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Registrar Pago</h5>
          <button type="button" class="close" data-dismiss="modal">
              <span>&times;</span>
          </button>
        </div>

      <div class="modal-body">
            <div class="form-group">
                <label>Monto a pagar</label>
                <input type="number" step="0.01" name="monto_total" id="monto_total"
                    class="form-control form-control-sm" required>
            </div>
            <div class="form-group">
                <label>Medio de pago</label>
                <select name="medio_pago" id="medio_pago"
                        class="form-control form-control-sm" required>
                    <option value="efectivo">Efectivo</option>
                    <option value="tarjeta">Tarjeta</option>
                    <option value="transferencia">Transferencia</option>
                </select>
            </div>
            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones" id="observaciones" rows="2"
                        class="form-control form-control-sm"></textarea>
            </div>
</div>

        

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success btn-sm">Confirmar Pago</button>
        </div>

      </form>

    </div>
  </div>
</div>

@section('js')
<script>
$('#modalPagar').on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget);
    let id     = button.data('id');
    let monto  = button.data('monto');

    $('#monto_total').val(monto);

    // Setear acción del formulario
    $('#formPagar').attr('action', '/estadias/' + id + '/pagar');
});
</script>
@endsection

@stop
