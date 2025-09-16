<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Reason;
use App\Models\Vehicle;
use App\Models\VehicleMovement;
use illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Traits\HandlesAtomicLocks;

class MovementsVehicleController extends Controller
{
    use HandlesAtomicLocks;
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
        })->orderBy('modelo', 'asc')->get();

        $drivers = Driver::whereNotIn('id', function ($query) {
            $query->select('driver_id')->from('vehicle_movements')->whereNull('data_retorno');
        })->orderBy('nome', 'asc')->get();
        $reasons = Reason::orderBy('descricao', 'asc')->get();

        return view('movements.create', compact('vehicles', 'drivers', 'reasons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'reason_id' => 'required|exists:reasons,id',
            'data_saida' => 'required|date',
            'estimativa_retorno' => [
                'required', 'date', 'after:data_saida',
                function ($attribute, $value, $fail) use ($request) {
                    $estimativa = Carbon::parse($value);
                    $saida = Carbon::parse($request->input('data_saida'));
                    if (abs($estimativa->diffInMinutes($saida, false)) < 10) {
                        $fail('A estimativa de retorno deve ser de no mínimo 10 minutos.');
                    }
                },
            ],
        ]);

        $lockKey = 'movement_for_vehicle_' . $validatedData['vehicle_id'];

        $wasSuccessful = $this->withLock($lockKey, function () use ($validatedData) {
            
            // 1. Verificação de disponibilidade corrigida e simplificada
            $isNotAvailable = VehicleMovement::whereNull('data_retorno')
                ->where(function ($query) use ($validatedData) {
                    $query->where('vehicle_id', $validatedData['vehicle_id'])
                        ->orWhere('driver_id', $validatedData['driver_id']);
                })
                ->exists();

            // 2. Lógica corrigida: Se NÃO estiver disponível, falha.
            if ($isNotAvailable) {
                return false; // Falha, pois o veículo ou motorista já está em uso
            }

            // Se estiver disponível, cria o registro e retorna sucesso.
            VehicleMovement::create($validatedData);
            return true;
        });

        if ($wasSuccessful) {
            return redirect()->route('movements.index')->with('success', 'Movimentação registrada com sucesso!');
        } else {
            return redirect()->route('movements.index');
        }
        
        // 3. Código inalcançável removido daqui.
    }



    public function returnForm($id)
    {
        $movement = VehicleMovement::with(['vehicle', 'driver', 'reason'])->findOrFail($id);

        return view('movements.return', compact('movement'));
    }

    public function returnUpdate(Request $request, $id)
    {
        // 1. A validação dos dados do formulário continua sendo o primeiro passo.
        $movementForValidation = VehicleMovement::findOrFail($id);
        $dadosValidados = $request->validate([
            'odometro' => 'required|numeric|gte:' . $movementForValidation->vehicle->odometro,
            'data_retorno' => 'required|date',
            'observacao' => 'nullable|string',
        ], [
            'odometro.gte' => 'O odômetro de retorno deve ser maior ou igual ao de saída (' . $movementForValidation->vehicle->odometro . ' Km).',
        ]);

        // 2. Cria a chave de bloqueio única para esta movimentação.
        $lockKey = 'updating_return_for_movement_' . $id;

        // 3. Executa a lógica de atualização dentro da trava atômica (sintaxe corrigida).
        $wasSuccessful = $this->withLock($lockKey, function () use ($dadosValidados, $id) {
            
            // Re-buscamos o movimento DENTRO da trava para garantir o estado mais atual.
            $movement = VehicleMovement::find($id);

            // 4. Lógica de verificação simplificada e corrigida.
            // Se o movimento existe E o retorno ainda não foi registrado...
            if ($movement && is_null($movement->data_retorno)) {
                
                // ...então atualizamos os dados.
                $movement->data_retorno = $dadosValidados['data_retorno'];
                $movement->observacao = $dadosValidados['observacao'];
                $movement->vehicle->odometro = $dadosValidados['odometro'];

                $movement->save();
                $movement->vehicle->save();

                return true; // Sucesso!
            }

            // Se a condição acima for falsa (movimento não existe ou já foi retornado), falha.
            return false;
        });
        
        // 5. Lida com o resultado da operação de forma clara.
        if ($wasSuccessful) {
            return redirect()->route('movements.index')->with('success', 'Retorno do veículo registrado com sucesso!');
        } else {
            // Redireciona de volta para o formulário com o erro.
            return redirect()->route('movements.index');
        }
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
