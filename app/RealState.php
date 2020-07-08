<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RealState extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'title', 'description', 'content',
        'price', 'slug', 'bathrooms', 'bedrooms',
        'property_area', 'total_property_area'
    ];

    /**
     * Relacionamento com a tabela users
     *
     * @return App\User;
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com a tabela categories
     *
     * @return array;
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'real_state_categories');
    }

    /**
     * Relacionamento com a tabela real_state_photos
     *
     * @return array;
     */
    public function photos()
    {
        return $this->hasMany(RealStatePhoto::class);
    }
}
