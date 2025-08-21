<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Reason;
use App\Models\Vehicle;
use App\Models\VehicleMovement;;
use Illuminate\Http\Request;

class MovementsVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
    */
    
    
    

    public function index(){
        
        $movements = VehicleMovement::with(['vehicle', 'driver', 'reason'])->get(); 
        
        return view('movements.index', compact('movements'));
        
    }
        /**$request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'reason_id' => 'required|exists:reasons,id',
            'data_saida' => 'required|date',
            'estimativa_retorno' => 'required|date',
            'data_retorno' => 'nullable|date|after:data_saida',
            'odometro' => 'required|integer|min:0|after:estimativa_retorno',
            'observacoes' => 'nullable|string|max:255',
        ]); */
    

    /**
     * Show the form for creating a new resource.
     */
    public function create(){
        $vehicles = Vehicle::all();
        $drivers = Driver::all();
        $reasons = Reason::all();
        
        return view('movements.create', compact('vehicles','drivers','reasons'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request -> validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'reason_id' => 'required|exists:reasons,id',
            'data_saida' => 'required|date',
            'estimativa_retorno' => 'required|date',
        ]);
        VehicleMovement::create($request->all());

        return redirect()->route('movements.index')->with('success', 'Movimentação registrada com sucesso');
    }
    public function returnForm($id)
    {
        $movement = VehicleMovement::with(['vehicle', 'driver','reason'])->findOrFail($id);
        
        return view('movements.return', compact('movement'));
    }

    public function returnUpdate(Request $request, $id){
        $movement = VehicleMovement::findOrFail($id);
        
        $movement->data_retorno = $request->input('data_retorno');
        $movement->odometro = $request->input('odometro');
        $movement->observacao = $request->input('observacao');
        $movement->save();
        
        return redirect()->route('movements.index')->with('success', 'Retorno registrado');
        
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $movement = \App\Models\VehicleMovement::find($id);
        return view('movements.index', ['movement' => $movement]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $movement = VehicleMovement::findOrFail($id);
        $reasons = Reason::all();
        $drivers = Driver::all();
        $vehicles = Vehicle::all();

        return view('movements.edit', compact ('reasons', 'drivers', 'vehicles', 'movement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $movement = VehicleMovement::find($request->id);
        if (!$movement) {
            return redirect()->back()->with('error', 'Movimentação não encontrada');
        }
        $request->validate([
            'odometro' => 'required|integer|min:0|after:estimativa_retorno',
            'observacao' => 'nullable|string|max:255',
        ]);

        $movement->update($request->all());

        return redirect(route('movements.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleMovement $vehicle_movements)
    {
        //
    }
}
