<?php

namespace Tests\Feature;

use App\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    /**
     * @test
     */
    public function not_authenticated_users_can_not_see_dashboard()
    {
        $this->get('/')->assertRedirect('login');

    }


    public function it_retrieves_last_3_payments_of_each_type()
    {
        $paymentA = factory(Payment::class)->create([]);
    }
}
