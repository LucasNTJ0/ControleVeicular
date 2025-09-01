
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Movimentações</title>
</head>
<body class="bg-blue-50">
    <div class="div-button">
        <a class="button-default" href="{{ route('movements.index')}}">Início</a>
    </div>
    <div class="table-movements">
        <h1 class="h2-principal">Todas as Movimentações Finalizadas</h1>
        <table class="table-default">
            <thead>
                <tr class="bg-gray-200">
                    <th class="th-default">Veículo</th>
                    <th class="th-default">Motorista</th>
                    <th class="th-default">Motivo</th>
                    <th class="th-default">Saída</th>
                    <th class="th-default">Estimativa de Retorno</th>
                    <th class="th-default">Retorno</th>
                    <th class="th-default">Valor <br> Odômetro</th>
                    <th class="th-default">Observações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($movements as $movement)
                    <tr class="hover:bg-gray-50 bg-white">
                        <td class="td-default">
                            {{ $movement->vehicle->placa }} :
                            {{ $movement->vehicle->modelo == 'Bongo'
                                ? 'Bongo 🚚'
                                : ($movement->vehicle->modelo == 'Strada'
                                    ? 'Strada 🚙'
                                    : ($movement->vehicle->modelo == 'Cobalt'
                                        ? 'Cobalt🚗'
                                        : ($movement->vehicle->modelo == 'Daily'
                                            ? 'Daily 🚛'
                                            : $movement->vehicle->modelo))) }}
                        </td>
                        <td class="td-default">{{ $movement->driver->nome }}</td>
                        <td class="td-default">{{ $movement->reason->descricao }}</td>
                        <td class="td-default">{{ $movement->data_saida }}</td>
                        <td class="td-default">{{ $movement->estimativa_retorno }}</td>
                        <td class="td-default">{{ $movement->data_retorno }}</td>
                        <td class="td-default">{{ $movement->odometro }}</td>
                        <td class="td-default">{{ $movement->observacao }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>
        {{ $movements->links() }}
    </div>
</body>
</html>