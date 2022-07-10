<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use SoftDeletes;

    protected $with = ['divisa'];

    public function divisa()
    {
        return $this->belongsTo('App\Divisa', 'divisa_id', 'id');
    }
}
