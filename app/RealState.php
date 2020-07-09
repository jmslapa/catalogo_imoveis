<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RealState extends Model
{

    protected $appends = ['_show', '_thumb'];

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

    public function getShowAttribute()
    {
        return [
            'href' => route('search.show', $this->id),
            'rel' => 'Real States'
        ];
    }

    public function getThumbAttribute() {
        $thumb = $this->photos()->where('is_thumb', true);
        if($thumb->count()) {
            return [
                'href' => url("/storage/{$thumb->first()->photo}"),
                'rel' => "Real State Photos"
            ];
        }
    }

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

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
