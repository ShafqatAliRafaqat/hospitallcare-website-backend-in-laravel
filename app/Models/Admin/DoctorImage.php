<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class DoctorImage extends Model
{
    use SoftDeletes;
    protected $table = 'doctor_images';
    protected $guarded = ['id'];
    public function Doctor(){
        return $this->belongsTo('App\Models\Admin\Doctor');
    }

}
