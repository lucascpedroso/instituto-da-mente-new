<?php

use App\Models\User;

it('redirects guests to the login page', function () {
    $this->get('/admin')->assertRedirect('/admin/login');
    $this->get('/admin/contatos')->assertRedirect('/admin/login');
});

it('gives administrators access to every section', function (string $url) {
    $this->actingAs(User::factory()->create(['role' => 'admin']))->get($url)->assertOk();
})->with(['/admin', '/admin/contatos', '/admin/depoimentos', '/admin/blog', '/admin/livros', '/admin/cursos', '/admin/terapias', '/admin/profissionais', '/admin/duvidas', '/admin/usuarios', '/admin/configuracoes']);

it('limits editors to blog and books', function () {
    $editor = User::factory()->create(['role' => 'editor']);

    $this->actingAs($editor)->get('/admin/blog')->assertOk();
    $this->actingAs($editor)->get('/admin/livros')->assertOk();
    $this->actingAs($editor)->get('/admin/contatos')->assertForbidden();
    $this->actingAs($editor)->get('/admin/usuarios')->assertForbidden();
    $this->actingAs($editor)->get('/admin/configuracoes')->assertForbidden();
});

it('denies users without a valid role', function () {
    $this->actingAs(User::factory()->create(['role' => 'visitante']))->get('/admin')->assertForbidden();
});
