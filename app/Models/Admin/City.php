<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
class City extends Model
{
    protected $guarded = ['id'];
    protected $table = 'cities_of_pak';
}
