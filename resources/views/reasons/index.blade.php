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
        <form action="{{ route('reasons.store')}}" method="POST">
            @csrf
            <div>
                <label for="reason_id">Escolha um Motivo</label>
                <select name="reason_id" id="reason_id">
                    <option value="" selected disabled>---Descrição do Motivo---</option>
                    @foreach ($reasons as $reason)
                        <option value=" {{ $reason->id}}">
                            {{ $reason->descricao}}
                        </option>
                    @endforeach
                </select>
                <button type="submit">Enviar</button>
            </div>
        </form>
    </div>
    <div>
        <form action="{{ route('reasons.store')}}" method="POST">
            @csrf
            <div>
                <label for="descricao">Descrição do Motivo</label>
                <input type="text" name="descricao" id="descricao" placeholder="Descrição do Motivo" required>
            </div>
            <button type="submit">Salvar Motivo</button>
        </form>
    </div>
</body>
</html>