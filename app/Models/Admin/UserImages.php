<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserImages extends Model
{
    use SoftDeletes;
    protected $table = 'users_images';
    protected $guarded = ['id'];
    public function User(){
    return $this->belongsTo('App\Models\User');
    }
}
