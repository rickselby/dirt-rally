<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;

class Nation extends \Eloquent
{
    use Sluggable;

    protected $fillable = ['name', 'acronym', 'dirt_reference'];

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }

    /**
     * Sluggable configuration
     *
     * @return array
     */
    public function sluggable()
    {
        // If there is no acronym to use as a slug, use the id
        return [
            'slug' => [
                'source' => $this->acronym ? 'acronym' : 'id',
            ]
        ];
    }
    
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
