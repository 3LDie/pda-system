<?php

use App\Models\User;

it('includes persisted theme initialization and a theme toggle control', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertOk();
    $response->assertSee('data-theme-toggle', false);
    $response->assertSee('Toggle dark mode', false);
    $response->assertSee('pda-theme', false);
});
