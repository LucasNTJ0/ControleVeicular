<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Retorno</title>
    @vite('resources/css/app.css')
</head>

<header>
    <h1 class="h1-principal">Controle de Veículos</h1>
</header>

<body class="bg-gray-50 text-gray-800">
    <h1 id="h2-return" class="text-xl font-semibold text-center mt-6 mb-4">Registrar Retorno do Veículo</h1>

    <div class="form-containerfull max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
        <form action="{{ route('movements.return', $movement->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Dados do movimento --}}
            <div class="form-return space-y-2">
                <div class="">
                    <span class="p-return">
                        Veículo:
                    </span>
                    <span class="p2-return">
                        {{ $movement->vehicle->placa }} : {{ $movement->vehicle->modelo }}
                    </span>
                </div>
                <div>
                    <span class="p-return">
                        Motorista:
                    </span>
                    <span class="p2-return">
                        {{ $movement->driver->nome }}
                    </span>
                </div>
                <div>
                    <span class="p-return">
                        Motivo:
                    </span>
                    <span class="p2-return">
                        {{ $movement->reason->descricao }}
                    </span>
                </div>
                <div>
                    <span class="p-return">
                        Data de Saída:
                    </span>
                    <span class="p2-return">
                        {{ $movement->data_saida->format('d/m/Y H:i') }}
                    </span>
                </div>
                <div>
                    <span class="p-return">
                        Último Valor de Odômetro:
                    </span>
                    <span class="p2-return">
                        {{ $movement->vehicle->odometro }}
                    </span>
                </div>
            </div>

            {{-- Inputs de retorno --}}
            <div class="flex flex-col gap-1">
                <label class="p-return">Data de Retorno:</label>
                <input type="datetime-local" name="data_retorno" class="input-return"
                    value="{{ now()->format('Y-m-d\TH:i') }}" required>
            </div>

            <div class="flex flex-col gap-1">
                <label class="p-return">Odômetro:</label>
                <input type="number" name="odometro" class="input-return" placeholder="Quilometragem Atual"
                    value="{{ old('vehicle_id', $movement->odometro) }}" required>
            </div>

            <div class="flex flex-col gap-1">
                <label class="p-return">Observações:</label>
                <textarea name="observacao" rows="2" class="input-return resize-y" placeholder="Digite observações adicionais..."></textarea>
            </div>

            {{-- Botão --}}
            <div class="pt-2">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow w-full cursor-pointer">
                    Finalizar Retorno
                </button>
            </div>
        </form>
    </div>
</body>

</html>
