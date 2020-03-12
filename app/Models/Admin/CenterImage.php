<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class CenterImage extends Model
{
    use SoftDeletes;
    protected $table = 'center_images';
    protected $guarded = ['id'];

    public function Center(){
        return $this->belongsTo('App\Models\Admin\Center');
    }
}
