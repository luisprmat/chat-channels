<?php

use App\Pulse\Recorders\ReverbConnections;
use Carbon\CarbonImmutable;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Support\Facades\DB;
use Laravel\Pulse\Events\IsolatedBeat;
use Laravel\Pulse\Facades\Pulse;
use Laravel\Reverb\Pulse\Livewire\Connections;
use Laravel\Reverb\Pulse\Recorders\ReverbConnections as BaseReverbConnections;
use Livewire\Livewire;
use Pusher\Pusher;

test('it records reverb connections using the broadcasting client options', function () {
    config(['broadcasting.connections.reverb.client_options' => ['verify' => false]]);

    $pusher = Mockery::mock(Pusher::class);
    $pusher->shouldReceive('get')->with('/connections')->andReturn((object) ['connections' => 2]);

    $broadcast = Mockery::mock(BroadcastManager::class);
    $broadcast->shouldReceive('pusher')
        ->withArgs(fn (array $config): bool => $config['client_options'] === ['verify' => false])
        ->once()
        ->andReturn($pusher);

    app()->instance(BroadcastManager::class, $broadcast);

    Pulse::startRecording();

    $recorder = app(BaseReverbConnections::class);

    expect($recorder)->toBeInstanceOf(ReverbConnections::class);

    $recorder->record(new IsolatedBeat(CarbonImmutable::parse('2026-11-02 10:00:15')));

    Pulse::ingest();

    expect(DB::table('pulse_aggregates')->where('type', 'reverb_connections')->where('aggregate', 'max')->value('value'))
        ->toEqual(2);
});

test('the reverb connections card renders', function () {
    Livewire::test(Connections::class)->assertOk();
});
