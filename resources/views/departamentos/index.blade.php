@extends('adminlte::page')

@section('title', 'Departamentos')

@section('content_header')
    <h1>Departamentos</h1>
@stop

@section('content')

<a href="{{ route('departamentos.create') }}" class="btn btn-primary btn-sm mb-3">
    <i class="fa fa-plus"></i> Nuevo Departamento
</a>

<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Piso</th>
                    <th>Número</th>
                    <th width="120">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departamentos as $d)
                    <tr>
                        <td>{{ $d->id }}</td>
                        <td>{{ $d->nombre }}</td>
                        <td>{{ $d->direccion }}</td>
                        <td>{{ $d->piso }}</td>
                        <td>{{ $d->numero }}</td>
                        <td>
                            <a href="{{ route('departamentos.edit', $d) }}"
                               class="btn btn-warning btn-xs">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="{{ route('departamentos.destroy', $d) }}"
                                  method="POST" style="display:inline-block;"
                                  onsubmit="return confirm('¿Eliminar departamento?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-xs">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                @if($departamentos->isEmpty())
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No hay departamentos cargados.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@stop
