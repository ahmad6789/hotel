<?php

namespace Modules\Ticket\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketActivity extends Model
{
    use HasFactory;
    protected $table='ticket_activities';
    protected $fillable = [];
    use SoftDeletes;
    protected static function newFactory()
    {
        return \Modules\Ticket\Database\factories\TicketActivityFactory::new();
    }
}
