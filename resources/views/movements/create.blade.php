<!DOCTYPE html>
<html lang="pt-br">
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        @if (app()->environment('local'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
        @php
            $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
            @endphp
        <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/app.css']['file']) }}">
        <script src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}" defer></script>
        @endif
        
        <title>Registrar Saída</title>
    </head>
    
    <header>
        <h1 class="h1-principal">Gerenciamento de Movimentações</h1>
    </header>

    <body>
        <h1 id="h2-principal">Registrar Saída de Veículo</h1>
        <div class="w-full max-w-sm mx-auto flex flex-col items-start gap-2">
            <div class=" absolute button-minor">
                <a href="{{ route('movements.index') }}">↩</a>
            </div>
            <div class="form-containerfull">
            <form action="{{ route('movements.store') }}" method="POST" novalidate>
                @csrf
                <div class="div-form">
                    <label for="vehicle" class="form-label">Veículo:</label>
                    <select name="vehicle_id" id="vehicle" class="form-select ">
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
                    <input type="datetime-local" name="estimativa_retorno" id="estimativa_retorno" class="form-select @error('estimativa_retorno') !border-red-600 @enderror"
                        value="{{ now()->format('Y-m-d\TH:i') }}"
                        min="{{ now()->addMinutes(10)->format('Y-m-d\TH:i') }}" required>
            
                    @error('estimativa_retorno')
                        <span id="mt-1" class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class=" text-gray-900 text-base font-medium mb-1 pt-2 pl-4 text-center ">
                    <label for="">Data de Saída: </label>
                    <input type="datetime-local" name="data_saida" id="data_saida"
                        value="{{ now()->format('Y-m-d\TH:i') }}" readonly>
                </div>
                <div class="flex justify-center">
                    <button class="button-default" type="submit">Registrar Saída</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
