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
    @if (session('success'))
        <div style="color: green; font-weight: bold; margin: 10px 0;">
            {{ session('success') }}
        </div>
    @endif

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
                <label for="driver">Motorista</label>
                <select name="driver_id" id="driver">
                    <option value="" disabled selected required>Escolha um Motorista</option>
                    @foreach ($drivers as $driver)
                        <option value="{{ $driver->id }}">{{ $driver->nome }}</option>
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
                <label for="">Data/Hora de Saída</label>
                <input type="datetime-local" name="data_saida" id="data_saida" required>
            </div>

            <div>
                <label for="">Estimativa de Retorno</label>
                <input type="datetime-local" name="estimativa_retorno" id="estimativa_retorno" required>
            </div>


            <button type="submit">Registrar Saída</button>
            <a href="{{ route('movements.index') }}">Ir para Pagina inicial</a>

        </form>
    </div>

</body>

</html>
