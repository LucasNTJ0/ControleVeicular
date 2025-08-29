<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriversController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $drivers = Driver::all();
        return view('drivers.index', compact('drivers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //return view('vehicle_movements', compact('drivers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request ->validate([
            'nome' => 'required|string|max:100',
            'cpf' => 'required|string|unique:drivers,cpf',
        ]);

        Driver::create($request->all());
        return redirect()->route('drivers.index')->with('sucess, Driver created succesfully');

    }

    /*public function processSelection(Request $request){
        $request ->validate([
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $driver = Driver::find($request->driver_id);
        return redirect()->route ('vehicle.create', ['driver' => $driver]);

    }*/
    /**
     * Display the specified resource.
     */
    public function show(Driver $drivers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Driver $drivers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Driver $drivers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $drivers)
    {
        //
    }
}
