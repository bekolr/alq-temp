<?php

namespace App\Http\Controllers;

use App\Models\Inquilino;
use Illuminate\Http\Request;

class InquilinoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
          $inquilinos = Inquilino::orderBy('nombre')->get();
        return view('inquilinos.index', compact('inquilinos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('inquilinos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
          $data = $request->validate([
            'nombre'    => 'required|string|max:255',
            'apellido'  => 'nullable|string|max:255',
            'dni'       => 'nullable|string|max:20',
            'telefono'  => 'nullable|string|max:30',
            'email'     => 'nullable|email',
            'direccion' => 'nullable|string',
        ]);

        Inquilino::create($data);

        return redirect()->route('inquilinos.index')
            ->with('success', 'Inquilino creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inquilino $inquilino)
    {
        //

        // Si querés evitar hacer vista show, lo redirigimos a editar
        return redirect()->route('inquilinos.edit', $inquilino);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inquilino $inquilino)
    {
        //
        return view('inquilinos.edit', compact('inquilino'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inquilino $inquilino)
    {
        //
           $data = $request->validate([
            'nombre'    => 'required|string|max:255',
            'apellido'  => 'nullable|string|max:255',
            'dni'       => 'nullable|string|max:20',
            'telefono'  => 'nullable|string|max:30',
            'email'     => 'nullable|email',
            'direccion' => 'nullable|string',
        ]);

        $inquilino->update($data);

        return redirect()->route('inquilinos.index')
            ->with('success', 'Inquilino actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inquilino $inquilino)
    {
        //
        $inquilino->delete();

         return redirect()->route('inquilinos.index')
            ->with('success', 'Inquilino eliminado correctamente.');
    }
}
