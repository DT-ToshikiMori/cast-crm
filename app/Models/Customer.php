<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'birthday', 'tag'];

    protected $casts = [
        'tag' => 'array',
    ];

    public function memos()
    {
        return $this->hasMany(Memo::class)->orderByDesc('date');
    }

    public function visits()
    {
        return $this->hasMany(Visit::class)->orderByDesc('date');
    }

    /**
     * Blade の $customer['memo'] 互換
     */
    public function getMemoAttribute()
    {
        return $this->memos;
    }

    /**
     * 最終来店日（文字列）
     */
    public function getLastVisitAttribute(): ?string
    {
        return $this->visits()->max('date');
    }

    /**
     * 最終来店からの経過日数
     */
    public function getDaysSinceLastVisitAttribute(): int
    {
        $last = $this->last_visit;
        if (!$last) {
            return 0;
        }
        return (int) abs(Carbon::now()->startOfDay()->diffInDays(Carbon::parse($last)->startOfDay()));
    }
}
