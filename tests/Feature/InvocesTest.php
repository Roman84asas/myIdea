<?php

namespace Tests\Feature;


use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvocesTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */

    public function not_authenticated_users_cant_create_a_new_invoices()
    {
        $this->withoutExceptionHandling([AuthenticationException::class]);
        $user = factory(User::class)->create();


        $this->get('invoices/new')
            ->assertRedirect('login');
    }


    /**
     *@test
     */

    public function customer_can_see_form_creating_new_invoice()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();


        $this->actingAs($user)
            ->get('invoices/new')
            ->assertStatus(200)
            ->assertSee('create new Invoice');
    }
}
