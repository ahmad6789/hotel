<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardOrPunishment extends Model
{
    use HasFactory;
    protected $filable = [
        'customer_id','labrere','type','price','why','date','created_at','updated_at'

    ];
    protected $table = 'rewardorpunishment';
}
