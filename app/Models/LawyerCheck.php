<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LawyerCheck extends Model
{
    protected $fillable = [
        'content', 'lawyer_id', 'checked_by', 'status'
    ];

    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class);
    }
}
