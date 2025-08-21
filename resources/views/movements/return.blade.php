<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1>Registrar Retorno do Veículo</h1>

    <form action="{{ route('movements.return', $movement->id) }}" method="POST">
        @csrf
        @method('PUT')

        <p><strong>Veículo:</strong> {{ $movement->vehicle->placa }} |-| {{ $movement->vehicle->modelo}}</p>
        <p><strong>Motorista:</strong> {{ $movement->driver->nome }}</p>
        <p><strong>Motivo:</strong> {{ $movement->reason->descricao }}</p>
        <p><strong>Data de Saída:</strong>{{ $movement->data_saida }}</p>

        <label for="">Data de Retorno:</label>
        <input type="datetime-local" name="data_retorno" id="data_retorno" value="{{ old('data_retorno', $movement->data_retorno) }}" required>

        <label for="">Odometro:</label>
        <input type="number" name="odometro" id="odometro" value=" {{ old('vehicle_id', $movement->odometro)}}" required>


        <label for="">Observações</label>
        <input type="text" name="observacao" id="observacao" value="{{ old('observacao', $movement->observacao) }}">

        <button type="submit">Finalizar Retorno</button>
    </form>
</body>

</html>
