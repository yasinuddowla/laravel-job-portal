<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'name',
        'email',
        'cover_letter',
        'resume',
        'status',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
