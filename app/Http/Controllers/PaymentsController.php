<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Payments\PaymentCodeGenerator;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function create()
    {
        return view('payments.create');
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $request->validate([
            'email'  => 'required|email',
            'amount' => 'required|integer|min:100',
        ]);

        $request->user()->payments()->create([
            'amount'      => $request->amount,
            'currency'    => $request->currency,
            'email'       => $request->email,
            'name'        => $request->name,
            'description' => $request->description,
            'message'     => $request->message,
            'code'        => app(PaymentCodeGenerator::class)->generate(),

        ]);
    }
}
