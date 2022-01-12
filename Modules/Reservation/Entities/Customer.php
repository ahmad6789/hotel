<?php

namespace Modules\Reservation\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [];
    
	protected $table = 'customer';
	protected $primaryKey = 'id';
	
    protected static function newFactory()
    {
        return \Modules\Reservation\Database\factories\CustomerFactory::new();
    }
}
