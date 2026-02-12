<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = ['user_id', 'customer_id', 'type', 'date', 'time', 'amount', 'note'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * 未整理ビューの $v['memo'] 互換（note のエイリアス）
     */
    public function getMemoAttribute()
    {
        return $this->note;
    }
}
