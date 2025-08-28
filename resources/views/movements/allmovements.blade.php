
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Movimentações</title>
</head>
<body>
    <div>
        <a href="{{ route('movements.index')}}">Início</a>
    </div>
    <div>
        <h1>Todas as Movimentações Finalizadas</h1>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <th>Veículo</th>
                <th>Motorista</th>
                <th>Motivo</th>
                <th>Saída</th>
                <th>Estimativa de Retorno</th>
                <th>Retorno</th>
                <th>Valor <br> Odômetro</th>
                <th>Observações</th>
            </thead>
            <tbody>
                @foreach ($movements as $movement)
                    <tr>
                        <td>
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
                        <td>{{ $movement->driver->nome }}</td>
                        <td>{{ $movement->reason->descricao }}</td>
                        <td>{{ $movement->data_saida }}</td>
                        <td>{{ $movement->estimativa_retorno }}</td>
                        <td>{{ $movement->data_retorno }}</td>
                        <td>{{ $movement->odometro }}</td>
                        <td>{{ $movement->observacao }}</td>
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