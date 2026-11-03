<?php

use App\Models\Channel;
use App\Models\User;

use function Pest\Laravel\actingAs;

const HIDE_AND_RESTORE_PAGE = <<<'JS'
    () => {
        window.notReloaded = true;
        window.dispatchEvent(new PageTransitionEvent('pagehide', { persisted: true }));
        window.dispatchEvent(new PageTransitionEvent('pageshow', { persisted: true }));
    }
    JS;

test('every channel is left when the page is hidden', function () {
    actingAs(User::factory()->create());
    $channel = Channel::create(['name' => 'general']);
    $page = visit(route('workspace', $channel));
    $page->assertScript('Object.keys(window.Echo.connector.channels).length > 0');

    $page->script("() => window.dispatchEvent(new PageTransitionEvent('pagehide', { persisted: true }))");

    $page->assertScript('Object.keys(window.Echo.connector.channels).length === 0')
        ->assertNoJavaScriptErrors();
});

test('a page with channels is reloaded when restored from the back/forward cache', function () {
    actingAs(User::factory()->create());
    $channel = Channel::create(['name' => 'general']);
    $page = visit(route('workspace', $channel));

    $page->script(HIDE_AND_RESTORE_PAGE);

    $page->wait(1)
        ->assertScript('window.notReloaded === undefined')
        ->assertScript('Object.keys(window.Echo.connector.channels).length > 0')
        ->assertNoJavaScriptErrors();
});

test('a page without channels is not reloaded when restored from the back/forward cache', function () {
    actingAs(User::factory()->create());
    $page = visit(route('dashboard'));

    $page->script(HIDE_AND_RESTORE_PAGE);

    $page->wait(1)
        ->assertScript('window.notReloaded === true')
        ->assertNoJavaScriptErrors();
});
