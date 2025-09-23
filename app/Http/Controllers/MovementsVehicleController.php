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
                'required', 'date',
                function ($attribute, $value, $fail) use ($request) {

                    if(empty($value) || empty($request->input('data_saida'))){
                        return; // Se algum dos campos estiver vazio, não faz a validação adicional aqui.
                    }

                    $estimativa = Carbon::parse($value);
                    $saida = Carbon::parse($request->input('data_saida'));

                    if ($estimativa->isBefore($saida)) {
                        $fail('A estimativa de retorno deve ser posterior à data de saída.');
                    }

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

            // Se NÃO estiver disponível, falha.
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
        
    }



    public function returnForm($id)
    {
        $movement = VehicleMovement::with(['vehicle', 'driver', 'reason'])->findOrFail($id);

        return view('movements.return', compact('movement'));
    }

    // app/Http/Controllers/MovementsVehicleController.php

public function returnUpdate(Request $request, $id)
{
    // 1. Busca o movimento e o odômetro ATUAL do veículo para validação.
    $movementForValidation = VehicleMovement::findOrFail($id);
    
    $odometroAtual = $movementForValidation->vehicle->odometro;
    $odometroMax = $odometroAtual + 10000;
    $odometroAviso = $odometroAtual + 999;
    $odometroFormatado = number_format($movementForValidation->vehicle->odometro, 0, ',', '.');
    $warningMessage = "O valor do odômetro está muito acima do último registrado ({$odometroFormatado} km). Se estiver correto, por favor, confirme.";
    // 2. A sua validação original já está correta para este cenário.
    //    Ela garante que o novo odômetro é maior ou igual ao último registrado no veículo.
    $dadosValidados = $request->validate([
        'odometro' => 'required|numeric|max:' . $odometroMax . '| gte:' . $odometroAtual,
        'data_retorno' => 'required|date',
        'observacao' => 'nullable|string',
    ], [
        'odometro.gte' => 'O odômetro de retorno deve ser maior ou igual ao último registrado (' . $odometroFormatado . ' Km).',
        'odometro.max' => 'O valor inserido está muito acima do último registrado (' . $odometroFormatado . ' Km). Por favor, verifique e corrija o valor inserido.',
    ]);


    if($dadosValidados['odometro'] >= $odometroAviso && !$request->input('confirm_odometro')){
        session()->flash('warning', $warningMessage);
        return redirect()->back()->withInput();
    }
    
    
    $lockKey = 'updating_return_for_movement_' . $id;

    
    // 3. Executa a lógica de atualização de forma segura.
    $wasSuccessful = $this->withLock($lockKey, function () use ($dadosValidados, $id) {
        
        $movement = VehicleMovement::find($id);
        
        if ($movement && is_null($movement->data_retorno)) {
            
            $movement->data_retorno = $dadosValidados['data_retorno'];
            $movement->observacao = $dadosValidados['observacao'];
            
            // A) Preenche o odômetro que estava faltando NA MOVIMENTAÇÃO.
            $movement->odometro = $dadosValidados['odometro'];
            // B) Atualiza o odômetro principal DO VEÍCULO.
            $movement->vehicle->odometro = $dadosValidados['odometro'];
            
            $movement->save();
            $movement->vehicle->save();
            
            return true;
        }
        
        return false;
    });
    
    // 4. Redireciona com base no sucesso ou falha.
    if ($wasSuccessful) {
        return redirect()->route('movements.index')->with('success', 'Retorno do veículo registrado com sucesso!');
    } else {
        return redirect()->route('movements.index')->with('error', 'Não foi possível registrar o retorno, ele já pode ter sido feito por outro usuário.');
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
