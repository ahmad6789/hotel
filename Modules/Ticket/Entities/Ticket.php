<?php

namespace Modules\Ticket\Entities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Ticket\Database\factories\TicketFactory::new();
    }
}
