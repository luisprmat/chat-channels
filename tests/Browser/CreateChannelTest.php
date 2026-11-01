<?php

use App\Models\Channel;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('a created channel is shown in the sidebar', function () {
    actingAs(User::factory()->create());
    $channel = Channel::create(['name' => 'general']);

    $page = visit(route('workspace', $channel));

    $page->assertDontSee('random')
        ->click('@add-channel')
        ->type('@new-channel-input', 'random')
        ->keys('@new-channel-input', 'Enter')
        ->assertSee('random')
        ->assertNoJavaScriptErrors();

    expect(Channel::where('name', 'random')->exists())->toBeTrue();
});
