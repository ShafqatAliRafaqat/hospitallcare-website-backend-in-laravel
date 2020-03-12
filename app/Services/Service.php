<?php
namespace App\Services;

use Illuminate\Support\Facades\Validator;

class Service {
    protected function validateOrAbort($input,$rules){
        $validator = Validator::make($input,$rules);
        if($validator->fails()){
         $msg = $validator->errors()->first();
         return $msg;
        }
    }
}
