@extends('adminlte::page')

@section('title', 'Editar Inquilino')

@section('content_header')
    <h1>Editar Inquilino</h1>
@stop

@section('content')

<div class="card">
    <form action="{{ route('inquilinos.update', $inquilino) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Nombre</label>
                    <input type="text" name="nombre"
                           value="{{ old('nombre', $inquilino->nombre) }}"
                           class="form-control form-control-sm" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Apellido</label>
                    <input type="text" name="apellido"
                           value="{{ old('apellido', $inquilino->apellido) }}"
                           class="form-control form-control-sm">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>DNI</label>
                    <input type="text" name="dni"
                           value="{{ old('dni', $inquilino->dni) }}"
                           class="form-control form-control-sm">
                </div>
                <div class="form-group col-md-4">
                    <label>Teléfono</label>
                    <input type="text" name="telefono"
                           value="{{ old('telefono', $inquilino->telefono) }}"
                           class="form-control form-control-sm">
                </div>
                <div class="form-group col-md-4">
                    <label>Email</label>
                    <input type="email" name="email"
                           value="{{ old('email', $inquilino->email) }}"
                           class="form-control form-control-sm">
                </div>
            </div>

            <div class="form-group">
                <label>Dirección</label>
                <textarea name="direccion" rows="2"
                          class="form-control form-control-sm">{{ old('direccion', $inquilino->direccion) }}</textarea>
            </div>

        </div>
        <div class="card-footer">
            <a href="{{ route('inquilinos.index') }}" class="btn btn-secondary btn-sm">Volver</a>
            <button type="submit" class="btn btn-primary btn-sm">Actualizar</button>
        </div>
    </form>
</div>

@stop
