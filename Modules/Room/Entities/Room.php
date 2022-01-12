<?php

namespace Modules\Room\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;
	use SoftDeletes;

    protected $fillable = ['code', 'capacity', 'price', 'categoryid', 'status'];
	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rooms';
	
	/**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    protected static function newFactory()
    {
        return \Modules\Room\Database\factories\RoomFactory::new();
    }
}
