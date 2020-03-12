<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class TreatmentImages extends Model
{
    use SoftDeletes;
    protected $table = 'treatment_images';
    protected $guarded = ['id'];

    public function Treatment(){
        return $this->belongsTo('App\Models\Admin\Treatment');
    }
}
