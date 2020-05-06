<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class CareallPassCategory extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $table = 'careallpass_category';
}
