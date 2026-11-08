<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateDashboardRequest;
use App\Http\Requests\UpdateDashboardRequest;
use App\Models\Dashboard;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }
    public function store(CreateDashboardRequest $request)
    {
        $validatedData = $request->validated();
        Dashboard::create($validatedData);
        return redirect()->route('dashboards.index')
                         ->with('success', 'Dashboard creado exitosamente.');
    }
    public function update(UpdateDashboardRequest $request, Dashboard $dashboard)
    {
        $validatedData = $request->validated();
        $dashboard->update($validatedData);
        return redirect()->route('dashboards.index')
                         ->with('success', 'Dashboard actualizado exitosamente.');
    }
    public function destroy(Dashboard $dashboard)
    {
        $dashboard->delete();
        return redirect()->route('dashboards.index')
                         ->with('success', 'Dashboard eliminado exitosamente.');
    }
}
