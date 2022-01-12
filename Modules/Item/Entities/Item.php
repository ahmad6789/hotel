<?php

namespace Modules\Item\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class Item extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'items';
    protected $fillable = [];
    protected $primaryKey = 'id';
    protected static function newFactory()
    {
        return \Modules\Item\Database\factories\ItemFactory::new();
    }

}
