<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;


class VehiclesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicles = Vehicle::all();
        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        /*return view('vehicles.index', compact('vehicle'));*/
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $vehicle = $request->validate([
            'placa' => 'required|string|max:10|unique:vehicles,placa',
            'marca' => 'required|string|max:50',
            'modelo' => 'required|string|max:50',
            'ano' => 'required|integer|min:1886|max:' . (date('Y')+ 1),
        ]);

        Vehicle::create($vehicle);

        return redirect(route('vehicles.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }
}
