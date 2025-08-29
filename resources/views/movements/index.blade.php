<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    {{-- <meta http-equiv="refresh" content="250; url={{ route('movements.index') }}"> --}}
    <title>Tela Inicial</title>
</head>

<body class="bg-blue-200">
    <div>
        <h1 class="h1-principal">Gerenciamento de Movimentações</h1>
        <h2 class="h2-principal">Registros sem Retorno</h2>
        <table id="table-container" class="table-default">
            <thead>
                <tr class="bg-gray-200">
                    <th class="th-default">Veículo</th>
                    <th class="th-default">Motorista</th>
                    <th class="th-default">Motivo</th>
                    <th class="th-default">Estimativa de Retorno</th>
                    <th class="th-default">Status</th>
                    <th class="th-default">Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($movements as $movement)
                    <tr class="hover:bg-gray-50 bg-white">
                        <td class="td-default">{{ $movement->vehicle->placa }} :
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
                        <td class="td-default">{{ $movement->driver->nome }}</td>
                        <td class="td-default">{{ $movement->reason->descricao }}</td>
                        <td class="td-default">{{ $movement->estimativa_retorno }}</td>
                        <td class="td-default ">
                            @if ($movement->data_retorno)
                                <span style="color:rgb(6, 52, 179);">✅</span>
                            @elseif (now() > $movement->estimativa_retorno)
                                <span style="color:rgb(167, 8, 8);">🚨</span>
                            @else
                                <span style="color:rgb(40, 158, 11)">🚧</span>
                            @endif
                        </td>
                        <td class="td-default">
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
        <a class="h2-principal" href="{{ route('movements.create') }}">Registrar Saída</a>
    </div>
    <div>
        <h1 class="h2-principal">Últimas 10 Movimentações</h1>
        <table class="table-default">
            <thead>
                <tr class="bg-gray-200">
                    <th class="th-default">Veículo</th>
                    <th class="th-default">Motorista</th>
                    <th class="th-default">Motivo</th>
                    <th class="th-default">Retorno</th>
                    <th class="th-default">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allmovements as $movement)
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
                        <td class="td-default">{{ $movement->data_retorno }}</td>

                        <td class="td-default">
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
        <a class="h2-principal" href="{{ route('movements.allmovements') }}">Todas Movimentações</a>
    </div>

</body>

</html>
