<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h1>Gerenciamento de Movimentações</h1>
    <h1>Registrar Saída de Veículo</h1>

    <div class="form-containerFULL">
        <form action="{{ route('movements.store') }}" method="POST">
            @csrf
            <div>
                <label for="vehicle">Veículo</label>
                <select name="vehicle_id" id="vehicle">
                    <option value="" disabled selected required>Escolha um Veículo</option>
                    @foreach ($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}">
                            {{ $vehicle->modelo }} {{ $vehicle->placa }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="driver">Motorista:</label>
                <select name="driver_id" id="driver">
                    <option value="" disabled selected >Escolha um Motorista</option>
                    @foreach ($drivers as $driver)
                        <option value="{{ $driver->id }}">
                            {{ $driver->nome }} - {{ $driver->cpf}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="reason">Motivo</label>
                <select name="reason_id" id="reason">
                    <option value="" disabled selected required>Escolha um Motivo</option>
                    @foreach ($reasons as $reason)
                        <option value="{{ $reason->id }}">{{ $reason->descricao }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="">Data de Saída</label>
                <input type="datetime-local" name="data_saida" id="data_saida" value="{{ now()->format('Y-m-d\TH:i') }}" readonly>
            <div>
                <label for="">Estimativa de Retorno</label>
                <input type="datetime-local" name="estimativa_retorno" id="estimativa_retorno" value="{{ now()->format('Y-m-d\TH:i')}}" min="{{ now()->format('Y-m-d\TH:i') }}"   required>
            </div>


            <button type="submit" onclick="alert('Saída Registrada com sucesso')">Registrar Saída</button>
            <a href="{{ route('movements.index') }}">Ir para Pagina inicial</a>

        </form>
    </div>

</body>

</html>
