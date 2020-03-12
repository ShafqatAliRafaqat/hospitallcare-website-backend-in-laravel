<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class CustomerDoctorNotes extends Model
{
    use SoftDeletes;
    protected $guarded  =   ['id'];
    protected $table    =   'customer_doctor_notes';

    public function customer()
    {
        return $this->belongsTo('App\Models\Admin\Customer')->withTimestamps();
    }
}
