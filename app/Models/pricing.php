<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class pricing extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'name', // Web Design category name
        'duration', // slug = web-design
        'price', // slug = web-design
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function isSubcribedByuser($user_id)
    {
        return $this->transactions->
            where('user_id', $user_id)
            ->where('is_paid', true)
            ->where('ended_at', '>=', now())
            ->exists();
    }
}
