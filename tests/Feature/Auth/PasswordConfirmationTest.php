<?php

use App\Models\User;

use function Pest\Laravel\actingAs;

test('confirm password screen can be rendered', function () {
    actingAs(User::factory()->create())
        ->get(route('password.confirm'))
        ->assertOk();
});
