<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Vlogs extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'vlogs';
}
