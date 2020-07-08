<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'about', 'social_networks', 'phone', 'mobile_phone'
    ];

    /**
     * Model table name.
     *
     * @var string
     */
    protected $table = 'user_profile';

    /**
     * Relacionamento com a tabela users
     *
     * @return array
     */
    public function user() {
        return $this->belongsTo(User::class);
    }
}
