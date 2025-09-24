<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Retorno</title>
    @if (app()->environment('local'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        @php
            $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        @endphp
        <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/app.css']['file']) }}">
        <script src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}" defer></script>
    @endif


</head>


<body class="bg-blue-50 ">
    <header>
        <h1 class="h1-principal">Controle de Veículos</h1>
    </header>
    <h1 id="h2-return" class="text-xl font-semibold text-center mt-6 mb-4">Registrar Retorno do Veículo</h1>
    <div class="w-full max-w-sm mx-auto flex flex-col items-start gap-2">
        <div class=" absolute button-minor">
            <a href="{{ route('movements.index') }}">↩</a>
        </div>
        <div class="form-containerfull max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
            <form action="{{ route('movements.return', $movement->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Dados do movimento --}}
                <div class="form-return space-y-2">
                    <div class="">
                        <span class="p-return">
                            Veículo:
                        </span>
                        <span class="p2-return">
                            {{ $movement->vehicle->placa }} : {{ $movement->vehicle->modelo }}
                        </span>
                    </div>
                    <div>
                        <span class="p-return">
                            Motorista:
                        </span>
                        <span class="p2-return">
                            {{ $movement->driver->nome }}
                        </span>
                    </div>
                    <div>
                        <span class="p-return">
                            Motivo:
                        </span>
                        <span class="p2-return">
                            {{ $movement->reason->descricao }}
                        </span>
                    </div>
                    <div>
                        <span class="p-return">
                            Data de Saída:
                        </span>
                        <span class="p2-return">
                            {{ $movement->data_saida->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    <div>
                        <span class="p-return">
                            Último Valor de Odômetro:
                        </span>
                        <span class="p2-return">
                            {{ number_format($movement->vehicle->odometro, 0, ',', '.') }} Km
                        </span>
                    </div>
                </div>

                {{-- Inputs de retorno --}}
                <div class="flex flex-col gap-1">
                    <label class="p-return">Data de Retorno:</label>
                    <input type="datetime-local" name="data_retorno" class="input-return"
                        value="{{ now()->format('Y-m-d\TH:i') }}" required>
                </div>

                <div class="flex flex-col gap-1">
                    <label class="p-return">Odômetro:</label>
                    <input type="text" name="odometro" inputmode="numeric"
                        class="input-return @error('odometro') !border-red-600 @enderror"
                        placeholder="Quilometragem Atual"
                        value="{{ old('odometro', $movement->odometro > 0 ? number_format($movement->odometro, 0, ',', '.'): '') }}"
                        required
                        oninput="this.value = this.value
                        .replace(/[^0-9]/g, '')             
                        .replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // adiciona pontos como separador">

                    @error('odometro')
                        <span id="error-odometro" class="danger-message">{{ $message }}</span>
                    @enderror

                    @if (session('warning'))
                        <div class="warning-message" role="alert">
                            {{ session('warning') }}
                        </div>
                        <input type="hidden" name="confirm_odometro" value="true">
                    @endif
                </div>

                <div class="flex flex-col gap-1">
                    <label class="p-return">Observações:</label>
                    <textarea name="observacao" rows="2" class="input-return resize-y" placeholder="Digite observações adicionais...">{{ old('observacao') }}</textarea>
                </div>

                {{-- Botão --}}
                <div class="pt-2">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow w-full cursor-pointer">
                        Finalizar Retorno
                    </button>
                </div>
            </form>
        </div>
        <footer class="w-full py-3 text-center text-xs text-gray-600 mt-auto">
            Versão {{ config('app.version') }}
        </footer>
</body>

</html>
