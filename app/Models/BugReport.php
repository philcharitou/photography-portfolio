<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BugReport extends Model
{
    protected $fillable = [
        'title',
        'author',
        'body',
        'status',

        // Foreign Keys
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,);
    }
}
