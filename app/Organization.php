<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
use SoftDeletes;

    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public function User()
    {
        return $this->hasMany('App\Organization');
    }
}
