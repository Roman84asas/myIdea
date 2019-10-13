<?php

namespace Tests\Feature;


use App\Payment;
use App\Payments\FakePaymentCodeGenerator;
use App\Payments\PaymentCodeGenerator;
use App\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentsTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     */

    public function not_authenticated_users_cant_create_a_new_invoices()
    {
        $this->withoutExceptionHandling([AuthenticationException::class]);
        $user = factory(User::class)->create();


        $this->get('payments/new')
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
            ->get('payments/new')
            ->assertStatus(200)
            ->assertSee('create new Payments');
    }

    /**
     *@test
     */

    public function user_can_create_a_new_payment()
    {
        $this->withoutExceptionHandling([AuthenticationException::class]);

        $response = $this->json('post', 'payments', [
            'email'       => 'bradly@cooper.com',
            'amount'      => 5000,
            'currency'    => 'usd',
            'name'        => 'Bradly Cooper',
            'description' => 'Pay me, NOW',
            'message'     => 'Hello',
        ]);

        $response->assertStatus(401);
        $this->assertEquals(0, Payment::count());
    }


    /**
     *@test
     */

    public function create_user_and_get_param()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();

        $fakePaymentCodeGenerator = new FakePaymentCodeGenerator;
        $this->app->instance(PaymentCodeGenerator::class, $fakePaymentCodeGenerator);


        $response = $this->actingAs($user)
            ->json('post', 'payments', [
            'email'       => 'bradly@cooper.com',
            'amount'      => 5000,
            'currency'    => 'usd',
            'name'        => 'Bradly Cooper',
            'description' => 'Pay me, NOW',
            'message'     => 'Hello',
        ]);

        $response->assertStatus(200);
        $this->assertEquals(1, Payment::count());

        tap(Payment::first(), function ($payment) use ($user)
        {
            $this->assertEquals($user->id, $payment->user_id);
            $this->assertEquals('bradly@cooper.com', $payment->email);
            $this->assertEquals(5000, $payment->amount);
            $this->assertEquals('usd', $payment->currency);
            $this->assertEquals('Bradly Cooper', $payment->name);
            $this->assertEquals('Pay me, NOW', $payment->description);
            $this->assertEquals('Hello', $payment->message);
            $this->assertEquals('QOWMEUT2K6S', $payment->code);
        });
    }

    /**
     *@test
     */

    public function email_field_is_required_to_create_a_payment()
    {
        $user = factory(User::class)->create();


        $response = $this->actingAs($user)->json('post', 'payments', [
            //'email' => 'bradly@cooper.com',
            'amount'      => 5000,
            'currency'    => 'usd',
            'name'        => 'Bradly Cooper',
            'description' => 'Pay me, NOW',
            'message'     => 'Hello',
        ]);

        $response->assertStatus(422);
        $this->assertEquals(0, Payment::count());
        $response->assertJsonValidationErrors('email');
    }


    /**
     *@test
     */

    public function email_field_should_be_a_valid_email_to_create_a_payment()
    {
        $user = factory(User::class)->create();


        $response = $this->actingAs($user)->json('post', 'payments', [
            'email'       => 'not-valid-email',
            'amount'      => 5000,
            'currency'    => 'usd',
            'name'        => 'Bradly Cooper',
            'description' => 'Pay me, NOW',
            'message'     => 'Hello',
        ]);

        $response->assertStatus(422);
        $this->assertEquals(0, Payment::count());
        $response->assertJsonValidationErrors('email');
    }

    /**
     *@test
     */

    public function amount_field_should_be_integer_to_create_a_payment()
    {
        $user = factory(User::class)->create();


        $response = $this->actingAs($user)->json('post', 'payments', [
            'email'       => 'not-valid-email',
            'amount'      => 'some-amount',
            'currency'    => 'usd',
            'name'        => 'Bradly Cooper',
            'description' => 'Pay me, NOW',
            'message'     => 'Hello',
        ]);

        $response->assertStatus(422);
        $this->assertEquals(0, Payment::count());
        $response->assertJsonValidationErrors('amount');
    }

}
