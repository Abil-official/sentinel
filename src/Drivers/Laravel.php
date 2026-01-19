<?php

namespace Laravel\Sentinel\Drivers;

use Illuminate\Http\Request;

class Laravel extends Driver
{
    /**
     * Authorize access the request.
     */
    public function authorize(Request $request): bool
    {
        if (! $this->app->environment('local')) {
            return true;
        }

        return $this->authorizeAccessingViaReverseProxies($request);
    }
}
