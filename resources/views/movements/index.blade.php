<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="refresh" content="5; url={{ route('movements.index') }}">
    <title>Movimentação</title>
</head>

<body>
    <h1>Gerenciamento de Movimentações</h1>
    <h2>Registro de Saídas</h2>
    {{-- <table border="1" cellpadding="10">
        <thead>
            <th>Veículo</th>
            <th>Motorista</th>
            <th>Motivo</th>
            <th>Data de Saída</th>
            <th>Estimativa de Retorno</th>
        </thead>
        <tbody>
            @foreach ($movements as $movement)
                <tr>
                    <td>{{ $movement->vehicle->placa }}</td>
                    <td>{{ $movement->driver->nome }}</td>
                    <td>{{ $movement->reason->descricao }}</td>
                    <td>{{ $movement->data_saida }}</td>
                    <td>
                        @if ($movement->data_retorno)
                            {{ $movement->data_retorno }}
                        @else
                            {{ 'Pendente' }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table> --}}
    <h1>Movimentações Recentes</h1>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Veículo</th>
                <th>Motorista</th>
                <th>Motivo</th>
                <th>Saída</th>
                <th>Retorno</th>
                <th>Ações</th>
                <th>Observações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($movements as $movement)
                <tr>
                    <td>{{ $movement->vehicle->modelo }} | {{ $movement->vehicle->placa }}</td>
                    <td>{{ $movement->driver->nome }}</td>
                    <td>{{ $movement->reason->descricao }}</td>
                    <td>{{ $movement->data_saida }}</td>
                    <td>
                        @if ($movement->data_retorno)
                            {{ $movement->data_retorno }}
                        @else
                            <span style="color:rgb(33, 0, 223);">Não retornado</span>
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
                    <td>
                        @if (!$movement->data_retorno)
                            <span>Sem Observações</span>
                        @else
                            <span>{{ $movement->observacao }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        <a href="{{ route('movements.create') }}">Registrar Novo Registro</a>
    </div>

</body>

</html>
