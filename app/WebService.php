<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebService extends Model
{
    protected $table    =   'web_services';
    protected $guarded  = ['id'];
}
