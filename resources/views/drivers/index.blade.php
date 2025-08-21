
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div>
        <form action="{{ route('drivers.store') }}" method="POST">
            @csrf
            <label for="driver_id">
                Escolha um motorista
            </label>
            <select name="driver_id" id="driver_id">
                <option value="" selected disabled>-- Selecione um motorista --</option>
                @foreach ($drivers as $driver)
                    <option value="{{ $driver->id }}">
                        {{ $driver->nome }} - CPF: {{ $driver->cpf }}
                    </option>
                @endforeach
            </select>
            <button type="submit">Continuar</button>
        </form>
    </div>
    <div>
        <form action="{{route('drivers.store')}}" method="POST">
            @csrf
            <div>
                <label for="">Cadastre o nome do Motorista</label>
                <input type="text" name="nome" placeholder="Nome do Motorista" required>
            </div>
            <div>
                <label for="">CPF do Motorista</label>
                <input type="text" name="cpf" id="cpf" placeholder="CPF do Motorista" required>
            </div>
            <button type="submit">Cadastrar</button>
        </form>
    </div>
</body>
</html>