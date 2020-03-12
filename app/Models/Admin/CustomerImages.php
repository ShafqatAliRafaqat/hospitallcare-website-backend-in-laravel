<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class CustomerImages extends Model
{    
    use SoftDeletes;
    protected $table    =   'customer_images';
    protected $guarded  =   ['id'];
    
    public function customer()
    {
        return $this->belongsTo('App\Models\Admin\Customer');
    }
}
