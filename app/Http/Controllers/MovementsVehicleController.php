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
    public function index()
    {

        $movements = VehicleMovement::with(['vehicle', 'driver', 'reason'])
        ->whereNull('data_retorno')
        ->orderBy('data_saida', 'desc')
        ->get();
        

        $allmovements = VehicleMovement::with(['vehicle', 'driver', 'reason'])
        ->whereNotNull('data_saida')
        ->orderBy('data_saida', 'desc')
        ->take(10)
        ->get();

        return view('movements.index', compact('movements', 'allmovements'));


    }


    public function allMovements()
    {
        $movements = VehicleMovement::with(['vehicle', 'driver', 'reason'])
        ->whereNotNull('data_retorno')
        ->orderBy('data_retorno', 'desc')
        ->paginate(10);
        
        return view('movements.allmovements', compact('movements'));
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
    public function create()
    {
        $vehicles = Vehicle::whereNotIn('id', function ($query) {
            $query->select('vehicle_id')->from('vehicle_movements')->whereNull('data_retorno');
        })->get();
        $drivers = Driver::whereNotIn('id', function ($query) {
            $query->select('driver_id')->from('vehicle_movements')->whereNull('data_retorno');
        })->get();
        $reasons = Reason::all();

        return view('movements.create', compact('vehicles', 'drivers', 'reasons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $vehicles = Vehicle::find($request->vehicle_id);
        $vehicles->save();

        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'reason_id' => 'required|exists:reasons,id',
            'data_saida' => 'required|date',
            'estimativa_retorno' => 'required|date:after:data_saida',
        ]);
        VehicleMovement::create($request->all());

        return redirect()->route('movements.index')->with('success', 'Movimentação registrada com sucesso');
    }
    public function returnForm($id)
    {
        $movement = VehicleMovement::with(['vehicle', 'driver', 'reason'])->findOrFail($id);

        return view('movements.return', compact('movement'));
    }

    public function returnUpdate(Request $request, $id)
    {
        $movement = new VehicleMovement;
        $movement->fill($request->all());
        $movement = VehicleMovement::findOrFail($id);
        $vehicles = Vehicle::find($movement->vehicle_id);

        $movement->data_retorno = $request->input('data_retorno');
        $movement->odometro = $request->input('odometro');
        $movement->vehicle->odometro = $request->input('odometro');
        $movement->observacao = $request->input('observacao');




        if ($movement->odometro < $vehicles->odometro) {
            return redirect()->back()->withInput()->with('error', 'O valor do odômetro deve ser maior ou igual ao valor de saída.');
        } else {
            $vehicles->odometro = $movement->odometro;
            $vehicles->save();
            $movement->vehicle->save();
            $movement->save();
        }

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

        return view('movements.edit', compact('reasons', 'drivers', 'vehicles', 'movement'));
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
