<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
    const NEW = 1;

    protected $guarded = [];
}
