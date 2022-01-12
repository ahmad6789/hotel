<?php

namespace Modules\Room\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bed extends Model
{
    use HasFactory;
	use SoftDeletes;

    protected $fillable = [];
	
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'room_beds';
	
	/**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    protected static function newFactory()
    {
        return \Modules\Room\Database\factories\BedFactory::new();
    }
}
