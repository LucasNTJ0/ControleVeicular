<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="form-containerP2">
        <h2>Registrar Retorno</h2>
        <form action="{{ route('movements.update', $movement->id) }}" method="POST">
            @csrf
            <div>
                <label for="">Veículo:</label>
                <select name="vehicle_id" id="vehicle_id">
                    <option value="" selected disabled>Selecione o Veículo de Retorno</option>
                    @foreach ($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}">
                            {{ $vehicle->id == $movement->vehicle_id ? 'selected' : '' }}> {{
                                    $vehicle->placa }} / {{ $vehicle->modelo 
                                }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="">Odômetro:</label>
                <input type="number" name="odometro" value="{{ $movement->odometro}}" id="odometro"  required>
            </div>
            <div>
                <label for="">Obsercações</label>
                <input type="text" name="observacao" id="observacao" value="{{ $movement->observacao}}" >
            </div>
            <button type="submit">Registrar Retorno</button>
        </form>
    </div>
</body>
</html>