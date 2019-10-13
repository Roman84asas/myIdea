<?php


namespace App\Payments;


class FakePaymentCodeGenerator implements PaymentCodeGenerator
{


    public function generate()
    {
        return 'QOWMEUT2K6S';
    }
}