<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDispositivoRequest;
use App\Http\Requests\UpdateDispositivoRequest;
use App\Models\Dispositivo;
use Illuminate\Http\Request;

class DispositivoController extends Controller
{
    public function index()
    {
        return response()->json(Dispositivo::all());
    }
    public function show(Dispositivo $dispositivo)
    {
        return response()->json($dispositivo);
    }
    public function store(CreateDispositivoRequest $request)
    {
        $dispositivo = Dispositivo::create($request->validated());
        return response()->json($dispositivo, 201);
    }
    public function update(UpdateDispositivoRequest $request, Dispositivo $dispositivo)
    {
        $dispositivo->update($request->validated());
        return response()->json($dispositivo);
    }
    public function destroy(Dispositivo $dispositivo)
    {
        $dispositivo->delete();
        return response()->json(null, 204);
}
}