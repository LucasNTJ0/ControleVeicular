<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="refresh" content="5; url={{ route('movements.index') }}">
    <title>Tela Inicial</title>
</head>
<body>
    <h1>Gerenciamento de Movimentações</h1>
    <div>
        <h2>Registros de Saídas sem Retorno</h2>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <th>Veículo</th>
                <th>Motorista</th>
                <th>Motivo</th>
                <th>Estimativa de Retorno</th>
                <th>Status</th>
                <th>Ação</th>
            </thead>
            <tbody>
                @foreach ($movements as $movement)
                    <tr>
                        <td>{{ $movement->vehicle->placa }} :
                            {{ $movement->vehicle->modelo == 'Bongo'
                                ? 'Bongo🚚'
                                : ($movement->vehicle->modelo == 'Strada'
                                    ? 'Strada🚙'
                                    : ($movement->vehicle->modelo == 'Cobalt'
                                        ? 'Cobalt🚗'
                                        : ($movement->vehicle->modelo == 'Daily'
                                            ? 'Daily🚛'
                                            : $movement->vehicle->modelo))) }}
                        </td>
                        <td>{{ $movement->driver->nome }}</td>
                        <td>{{ $movement->reason->descricao }}</td>
                        <td>{{ $movement->estimativa_retorno }}</td>
                        <td>
                            @if ($movement->data_retorno)
                                <span style="color:rgb(6, 52, 179);">✅</span>
                            @elseif (now() > $movement->estimativa_retorno)
                                <span style="color:rgb(167, 8, 8);">🚨</span>
                            @else
                                <span style="color:rgb(40, 158, 11)">🚧</span>
                            @endif
                        </td>
                        <td>
                            @if (!$movement->data_retorno)
                                <button
                                    onclick="window.location.href='{{ route('movements.returnForm', $movement->id) }}'">Registrar
                                    Retorno
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>
        <a href="{{ route('movements.create') }}">Registrar Saída</a>
    </div>
    <div>
        <h1>Últimas 10 Movimentações</h1>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <th>Veículo</th>
                <th>Motorista</th>
                <th>Motivo</th>
                <th>Retorno</th>
                <th>Status</th>
            </thead>
            <tbody>
                @foreach ($allmovements as $movement)
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
                        <td>{{ $movement->data_retorno }}</td>

                        <td>
                            @if ($movement->data_retorno)
                                <span style="color:rgb(6, 52, 179);">✅</span>
                            @elseif (now() > $movement->estimativa_retorno)
                                <span style="color:rgb(167, 8, 8);">🚨</span>
                            @else
                                <span style="color:rgb(40, 158, 11)">🚧</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <br>
    <div>
        <a href="{{ route('movements.allmovements') }}">Todas Movimentações</a>

    </div>

</body>

</html>
