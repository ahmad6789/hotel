<?php

namespace Modules\Item\Entities;
 use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
 class RoomItems extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [];
    protected $table = 'room_items';
    protected $primaryKey = ['roomid','itemid'];
    public $incrementing = false;

    protected static function newFactory()
    {
        return \Modules\Item\Database\factories\RoomItemFactory::new();
    }
     
}
