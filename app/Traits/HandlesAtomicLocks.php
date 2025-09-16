<?php

namespace App\Traits;

use Closure;
use Illuminate\Support\Facades\Cache;

trait HandlesAtomicLocks
{
    /**
     * Executa um bloco de código dentro de uma trava atômica para prevenir race conditions.
     *
     * @param  string  $lockKey A chave única para a trava.
     * @param  Closure $callback O código a ser executado se a trava for obtida.
     * @param  int     $lockoutSeconds O tempo de expiração da trava em segundos.
     * @return mixed O resultado da closure, ou false se a trava não for obtida.
     */
    protected function withLock(string $lockKey, Closure $callback, int $lockoutSeconds = 10)
    {
        return Cache::lock($lockKey, $lockoutSeconds)->get($callback);
    }
}