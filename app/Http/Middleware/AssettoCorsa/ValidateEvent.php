<?php

namespace App\Http\Middleware\AssettoCorsa;

class ValidateEvent
{
    use ValidateChain;

    public function handle($request, $next)
    {
        $request->attributes->add(['event' => $this->validateEvent($request)]);
        return $next($request);
    }
}