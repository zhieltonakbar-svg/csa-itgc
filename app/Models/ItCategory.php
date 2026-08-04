<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ItCategory extends Model
{
    protected $fillable = ['name', 'icon', 'description'];

    /**
     * The applications that include this IT category.
     */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(Application::class, 'application_it_category')
                    ->withPivot('completion_status')
                    ->withTimestamps();
    }
}
