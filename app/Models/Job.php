<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_listings';

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'location',
        'type',
        'salary_range',
        'is_active',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function views()
    {
        return $this->hasMany(JobView::class);
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }
}
