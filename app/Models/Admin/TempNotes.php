<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class TempNotes extends Model
{
    protected $table = 'temp_notes';
    protected $guarded = ['id'];
    
    public function temcustomer(){
        return $this->belongsTo('App\Models\Admin\TempCustomer');
    }
}
