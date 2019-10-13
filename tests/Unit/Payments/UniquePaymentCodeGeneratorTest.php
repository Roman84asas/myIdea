<?php

namespace Tests\Unit\Payments;

use App\Payments\UniquePaymentCodeGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UniquePaymentCodeGeneratorTest extends TestCase
{


    /**
     * @test
     */
    public function it_must_be_16_characters_long()
    {

        $generator = new UniquePaymentCodeGenerator;
        $code = $generator->generate();

        $this->assertEquals(16, strlen($code));

    }

    /**
     * @test
     */
    public function it_can_only_contain_uppercase_letters_end_numbers()
    {

        $generator = new UniquePaymentCodeGenerator;
        $code = $generator->generate();

        $this->assertRegExp('/^[A-Z0-9]*$/', $code);

    }

    /**
     * @test
     */
    public function code_must_be_unique()
    {
        $codes = collect();

        for ($i=0; $i < 1000; $i++) {
            $codes->push((new UniquePaymentCodeGenerator)->generate());
        }


        $this->assertEquals($codes->count(), $codes->unique()->count());

    }
}
