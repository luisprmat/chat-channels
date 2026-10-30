<?php

use App\Models\Channel;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    actingAs(User::factory()->create());

    /** @var TestCase $this */
    $this->channel = Channel::create(['name' => 'general']);
});

test('a channel can be created', function () {
    /** @var TestCase $this */
    Livewire::test('pages::workspace', ['channel' => $this->channel])
        ->set('name', 'random')
        ->call('createChannel')
        ->assertHasNoErrors()
        ->assertSet('name', '')
        ->assertDispatched('channel-created')
        ->assertSee('random');

    expect(Channel::where('name', 'random')->exists())->toBeTrue();
});

test('the channel name is validated', function (string $name, string $rule) {
    /** @var TestCase $this */
    Livewire::test('pages::workspace', ['channel' => $this->channel])
        ->set('name', $name)
        ->call('createChannel')
        ->assertHasErrors(['name' => $rule])
        ->assertSet('name', $name)
        ->assertNotDispatched('channel-created');

    expect(Channel::count())->toBe(1);
})->with([
    'empty' => ['', 'required'],
    'uppercase' => ['Random', 'lowercase'],
]);
