<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

test('login screen can be rendered', function () {
    get(route('login'))
        ->assertOk();
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    assertAuthenticated();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    post(route('login.store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])
        ->assertSessionHasErrorsIn('email');

    assertGuest();
});

test('users can logout', function () {
    actingAs(User::factory()->create())
        ->post(route('logout'))
        ->assertRedirect(route('home'));

    assertGuest();
});
