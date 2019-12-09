<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'title', 'icon'
    ];

    // 律师
    public function lawyers()
    {
        return $this->belongsToMany(Lawyer::class);
    }
}
