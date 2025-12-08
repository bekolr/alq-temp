@extends('adminlte::page')

@section('title', 'Inquilinos')

@section('content_header')
    <h1>Inquilinos</h1>
@stop

@section('content')

<a href="{{ route('inquilinos.create') }}" class="btn btn-primary btn-sm mb-3">
    <i class="fa fa-plus"></i> Nuevo Inquilino
</a>

<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre completo</th>
                    <th>DNI</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th width="120">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inquilinos as $inq)
                    <tr>
                        <td>{{ $inq->id }}</td>
                        <td>{{ $inq->nombre_completo }}</td>
                        <td>{{ $inq->dni }}</td>
                        <td>{{ $inq->telefono }}</td>
                        <td>{{ $inq->email }}</td>
                        <td>
                            <a href="{{ route('inquilinos.edit', $inq) }}"
                               class="btn btn-warning btn-xs">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('inquilinos.destroy', $inq) }}"
                                  method="POST" style="display:inline-block;"
                                  onsubmit="return confirm('¿Eliminar inquilino?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-xs">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if($inquilinos->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No hay inquilinos cargados.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@stop
