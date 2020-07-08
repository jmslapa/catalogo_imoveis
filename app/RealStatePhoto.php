<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RealStatePhoto extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'photo', 'is_thumb'
    ];

    /**
     * Relacionamento com a tabela real_states
     *
     * @return array
     */
    public function realState()
    {
        return $this->belongsTo(RealState::class);
    }
}
