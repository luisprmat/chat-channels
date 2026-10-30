<?php

use Laravel\Fortify\Features;
use Tests\TestCase;

use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

beforeEach(function () {
    /** @var TestCase $this */
    $this->skipUnlessFortifyHas(Features::registration());
});

test('registration screen can be rendered', function () {
    get(route('register'))->assertOk();
});

test('new users can register', function () {
    post(route('register.store'), [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    assertAuthenticated();
});
