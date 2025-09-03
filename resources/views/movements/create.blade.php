<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Registrar Saída</title>
</head>

<header>
    <h1 class="h1-principal">Gerenciamento de Movimentações</h1>
</header>

<body>
    <h1 id="h2-principal">Registrar Saída de Veículo</h1>
    <div class="form-containerfull">
        <form action="{{ route('movements.store') }}" method="POST">
            @csrf
            <div class="div-form">
                <label for="vehicle" class="form-label">Veículo:</label>
                <select name="vehicle_id" id="vehicle" class="form-select">
                    <option value="" disabled selected required>Escolha um Veículo</option>
                    @foreach ($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}">
                            {{ $vehicle->modelo }} {{ $vehicle->placa }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="div-form">
                <label for="driver" class="form-label">Motorista:</label>
                <select name="driver_id" id="driver" class="form-select">
                    <option value="" disabled selected class="form-select">Escolha um Motorista</option>
                    @foreach ($drivers as $driver)
                        <option value="{{ $driver->id }}">
                            {{ $driver->nome }} - {{ $driver->cpf }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="div-form">
                <label for="reason" class="form-label">Motivo:</label>
                <select name="reason_id" id="reason" class="form-select">
                    <option value="" disabled selected required>Escolha um Motivo</option>
                    @foreach ($reasons as $reason)
                        <option value="{{ $reason->id }}">{{ $reason->descricao }}</option>
                    @endforeach
                </select>
            </div>
            <div class="div-form">
                <label for="" class="form-label">Estimativa de Retorno: </label>
                <input type="datetime-local" name="estimativa_retorno" id="estimativa_retorno" class="form-select"
                    value="{{ now()->format('Y-m-d\TH:i') }}" min="{{ now()->format('Y-m-d\TH:i') }}" required>
            </div>
            <div class=" text-gray-900 text-base font-medium mb-1 pt-2 pl-4">
                <label for="">Data de Saída:  </label>
                <input type="datetime-local" name="data_saida" id="data_saida"
                    value="{{ now()->format('Y-m-d\TH:i') }}" readonly>
            </div>
            <div class="flex justify-center" >
                <button class="button-default" type="submit">Registrar Saída</button>
            </div>
            <a class="flex justify-center" href="{{ route('movements.index') }}">Ir para Pagina inicial</a>
        </form>
    </div>

</body>

</html>
