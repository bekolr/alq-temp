<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    public function index()
    {
        $departamentos = Departamento::orderBy('nombre')->get();
        return view('departamentos.index', compact('departamentos'));
    }

    public function create()
    {
        return view('departamentos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'    => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'piso'      => 'nullable|string|max:50',
            'numero'    => 'nullable|string|max:50',
        ]);

        Departamento::create($data);

        return redirect()->route('departamentos.index')
            ->with('success', 'Departamento creado correctamente.');
    }

    public function show(Departamento $departamento)
    {
        return redirect()->route('departamentos.edit', $departamento);
    }

    public function edit(Departamento $departamento)
    {
        return view('departamentos.edit', compact('departamento'));
    }

    public function update(Request $request, Departamento $departamento)
    {
        $data = $request->validate([
            'nombre'    => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'piso'      => 'nullable|string|max:50',
            'numero'    => 'nullable|string|max:50',
        ]);

        $departamento->update($data);

        return redirect()->route('departamentos.index')
            ->with('success', 'Departamento actualizado correctamente.');
    }

    public function destroy(Departamento $departamento)
    {
        $departamento->delete();

        return redirect()->route('departamentos.index')
            ->with('success', 'Departamento eliminado correctamente.');
    }
}
