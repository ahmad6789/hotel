<?php

namespace Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentLine extends Model
{
    use HasFactory;

    protected $fillable = [];
	
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_line';
    
    protected static function newFactory()
    {
        return \Modules\Payment\Database\factories\PaymentLineFactory::new();
    }
}
