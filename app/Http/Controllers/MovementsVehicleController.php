<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Reason;
use App\Models\Vehicle;
use App\Models\VehicleMovement;
use illuminate\Support\Carbon;
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
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'driver_id' => 'required|exists:drivers,id',
            'reason_id' => 'required|exists:reasons,id',
            'data_saida' => 'required|date',
            
            // 2. As regras para 'estimativa_retorno' devem estar dentro de um array
            'estimativa_retorno' => [
                'required',
                'date',
                
                function ($attribute, $value, $fail) use ($request) {
                    $estimativa = Carbon::parse($value);
                    $saida = Carbon::parse($request->input('data_saida'));
                    $minutesDifference = $estimativa->diffInMinutes($saida);
                    if ($minutesDifference < 10)  {
                        $fail('A estimativa de retorno deve ser de no mínimo 10 minutos.');
                    }
                },
            ], 
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
    // 1. Encontra o movimento e o veículo associado a ele
    $movement = VehicleMovement::findOrFail($id);
    $vehicles = $movement->vehicle; // Acessa o veículo pela relação já carregada

    // 2. Guarda o valor do odômetro ANTES de qualquer modificação
    $ultimo_odometro = $vehicles->odometro;

    // 3. VALIDAÇÃO PRIMEIRO!
    //    Compara o 'odometro' do formulário com o valor antigo do banco.
    $dadosValidados = $request->validate([
        'odometro' => 'required|numeric|gte:' . $ultimo_odometro,
        'data_retorno' => 'required|date',
        'observacao' => 'nullable|string', // Observação é opcional
    ], [
        // Mensagem de erro personalizada
        'odometro.gte' => 'O odômetro de retorno deve ser maior ou igual ao de saída (' . $ultimo_odometro . ' Km).',
    ]);

    // 4. Se a validação passou, o código continua. Agora atualizamos os dados.
    
    // Atualiza o registro do movimento
    $movement->data_retorno = $dadosValidados['data_retorno'];
    $movement->observacao = $dadosValidados['observacao'];
    // O odômetro do movimento é o de SAÍDA, então não o alteramos aqui.
    // Se precisar registrar o odômetro de retorno no movimento, crie uma coluna nova (ex: odometro_retorno)

    // Atualiza o odômetro principal do veículo
    $vehicles->odometro = $dadosValidados['odometro'];

    // 5. Salva as alterações nos dois modelos
    $movement->save();
    $vehicles->save();

    // 6. Redireciona para uma página de sucesso com uma mensagem flash
    return redirect()->route('movements.index')->with('success', 'Retorno do veículo registrado com sucesso!');
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
