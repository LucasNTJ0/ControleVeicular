<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Escolha o Veículo</h1>
    <div>
        <form action="" method="POST">
            @csrf
            <label for="">Veículo:</label>
            <select name="vehicle_id" id="vehicle_id">
                <option value="" selected disabled>--Selecione Um Veículo--</option>
            @foreach ($vehicles as $vehicle)
                <option value="{{ $vehicle->id}}">
                    {{ $vehicle->placa }} - {{ $vehicle->modelo }} - {{ $vehicle->ano}}
                </option>
            @endforeach
            </select>

            <button type="submit">Enviar</button>
        </form>
    </div>
    <br><br><br>
    <div>
        <form action="{{ route('vehicles.store')}}" method="POST">
            @csrf
            <div>
                <label for="">Placa do Veículo</label>
                <input type="text" name="placa" id="placa" placeholder="Placa do Veículo" required>
            </div>
            <div>
                <label for="">Marca do Veículo</label>
                <input type="text" name="marca" id="marca" placeholder="Marca do Veículo" required>
            </div>
            <div>
                <label for="">Modelo do Veículo</label>
                <input type="text" name="modelo" id="modelo" placeholder="Modelo do Veículo" required>
            </div>
            <div>
                <label for="">Ano do Veículo</label>
                <input type="number" name="ano" id="ano" placeholder="Ano do Veículo" required>
            </div>
            <button type="submit">Enviar</button>
        </form>
    </div>

</body>
</html>