<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Application extends Model
{
    protected $fillable = ['name', 'description', 'is_active', 'upti_id'];

    public function upti()
    {
        return $this->belongsTo(Upti::class);
    }

    /**
     * The IT categories that belong to this application,
     * along with the completion_status from the pivot table.
     */
    public function itCategories(): BelongsToMany
    {
        return $this->belongsToMany(ItCategory::class, 'application_it_category')
                    ->withPivot('completion_status')
                    ->withTimestamps();
    }
}
