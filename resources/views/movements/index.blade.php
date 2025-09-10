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
<header>
    <h1 class="h1-principal">Controle de Veículos</h1>
</header>

<body class="bg-blue-50">
    <div class="div-button">
        <a class="button-default" id="saida-button" href="{{ route('movements.create') }}"> + Registrar Saída</a>
    </div>
    <div class="table-movements">
        <h2 class="h2-principal">Registros sem Retorno</h2>
        <table class="table-default">
            <thead>
                <tr class="bg-gray-200">
                    <th class="th-default">Veículo</th>
                    <th class="th-default">Motorista</th>
                    <th class="th-default">Motivo</th>
                    <th class="th-default">Retorno Estimado</th>
                    <th class="th-default">Status</th>
                    <th class="th-default">Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($movements as $movement)
                    <tr class="tr-default">
                        <td class="td-default">{{ $movement->vehicle->placa }} :
                            {{ $movement->vehicle->modelo == 'Bongo'
                                ? 'Bongo🚚'
                                : ($movement->vehicle->modelo == 'Strada'
                                    ? 'Strada🚙'
                                    : ($movement->vehicle->modelo == 'Cobalt'
                                        ? 'Cobalt🚗'
                                        : ($movement->vehicle->modelo == 'Daily'
                                            ? 'Daily🚛'
                                            : ($movement->vehicle->modelo == 'Lead'
                                                ? 'Lead🛵'
                                                : $movement->vehicle->modelo)))) }}
                        </td>
                        <td class="td-default">{{ $movement->driver->nome }}</td>
                        <td class="td-default">{{ $movement->reason->descricao }}</td>
                        <td class="td-default">
                            {{ $movement->estimativa_retorno ? date('d/m/Y H:i', strtotime($movement->estimativa_retorno)) : '-' }}
                        </td>
                        <td class="td-default-status">
                            @if ($movement->data_retorno)
                                <div class="div-status">
                                    <span>✅</span>
                                    <span class="status-concluido">Concluído</span>
                                </div>
                            @elseif (now() > $movement->estimativa_retorno)
                                <div class="div-status">
                                    <span class="text-2xl">⚠</span>
                                    <span class="status-atraso">Atrasado</span>
                                </div>
                            @else
                                <div class="div-status">
                                    <span class="text-2xl">⏳</span>
                                    <span class="status-uso">Andamento</span>
                                </div>
                            @endif
                        </td>
                        <td class="td-default">
                            @if (!$movement->data_retorno)
                                <a id="return-button"
                                    onclick="window.location.href='{{ route('movements.returnForm', $movement->id) }}'">
                                    ↩ Registrar
                                    Retorno
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
                    <tr class="tr-default">
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
                                            : ($movement->vehicle->modelo == 'Lead'
                                                ? 'Lead🛵'
                                                : $movement->vehicle->modelo)))) }}
                        </td>
                        <td class="td-default">{{ $movement->driver->nome }}</td>
                        <td class="td-default">{{ $movement->reason->descricao }}</td>
                        <td class="td-default">
                            {{ $movement->data_retorno ? date('d/m/Y H:i', strtotime($movement->data_retorno)) : '' }}
                        </td>


                        <td class="td-default-status">
                            @if ($movement->data_retorno)
                                <div class="div-status">
                                    <span>✅</span>
                                    <span class="status-concluido">Concluído</span>
                                </div>
                            @elseif (now() > $movement->estimativa_retorno)
                                <div class="div-status">
                                    <span class="text-2xl">⚠</span>
                                    <span class="status-atraso">Atrasado</span>
                                </div>
                            @else
                                <div class="div-status">
                                    <span class="text-2xl">⏳</span>
                                    <span class="status-uso">Andamento</span>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <br>
    <div id="allmovements-button" class="div-button">
        <a class="button-default" href="{{ route('movements.allmovements') }}">Visualizar Todas Movimentações</a>
    </div>

</body>

</html>
