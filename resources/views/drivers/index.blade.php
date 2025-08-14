<div>
    <form action="{{ route('drivers.processSelection') }}" method="POST">
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