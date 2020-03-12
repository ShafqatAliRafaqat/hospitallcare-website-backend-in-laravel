<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class CenterPartnershipFiles extends Model
{
    use SoftDeletes;
    protected $table = 'center_partnership_files';
    protected $guarded = ['id'];

    public function Center(){
    return $this->belongsTo('App\Models\Admin\Center');
    }
}
