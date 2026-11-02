<?php

namespace App\Pulse\Recorders;

use Laravel\Pulse\Events\IsolatedBeat;
use Laravel\Reverb\Application;
use Laravel\Reverb\Contracts\ApplicationProvider;
use Laravel\Reverb\Pulse\Recorders\ReverbConnections as BaseReverbConnections;

/**
 * Records Reverb connection counts using the HTTP client options of the
 * "reverb" broadcasting connection (e.g. disabling TLS verification
 * for local self-signed certificates via REVERB_VERIFY_SSL).
 */
class ReverbConnections extends BaseReverbConnections
{
    /**
     * Record the connection count.
     */
    public function record(IsolatedBeat $event): void
    {
        if ($event->time->second % 15 !== 0) {
            return;
        }

        $this->app->make(ApplicationProvider::class)->all()
            ->each(function (Application $app) use ($event): void {
                $connections = $this->broadcast->pusher([
                    ...$app->toArray(),
                    'client_options' => config('broadcasting.connections.reverb.client_options', []),
                ])
                    ->get('/connections')
                    ->connections;

                $this->pulse->record(
                    type: 'reverb_connections',
                    key: $app->id(),
                    value: $connections,
                    timestamp: $event->time->getTimestamp(),
                )->avg()->max()->onlyBuckets();
            });
    }
}
