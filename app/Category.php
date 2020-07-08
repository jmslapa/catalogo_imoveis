<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'slug'
    ];

    /**
     * Relacionamento com a tabela real_states
     *
     * @return array
     */
    public function realStates() {
        return $this->belongsToMany(RealState::class, 'real_state_categories');
    }
}
