<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationPeriod extends Model
{
    protected $fillable = ['application_id', 'year', 'quarter'];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function itCategories()
    {
        return $this->belongsToMany(
            ItCategory::class,
            'application_period_it_category',
            'application_period_id',
            'it_category_id'
        )->withTimestamps();
    }
}
