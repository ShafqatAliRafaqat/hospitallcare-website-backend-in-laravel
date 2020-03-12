<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Whitepaper extends Model
{
    protected $guarded = [];
    protected $table = 'whitepapers';
    protected $fillable = ['title'];
}
