<?php

namespace Modules\Reservation\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [];
    
	protected $table = 'reservation';
	protected $primaryKey = 'id';
	
    protected static function newFactory()
    {
        return \Modules\Reservation\Database\factories\ReservationFactory::new();
    }
}
