<?php

namespace App\Helpers;


use App\FCMDevice;
use App\Notification;
use App\Services\PushNotificationService;
use App\User;

class NotificationHelper {


    public static function GENERATE($notification,$users){

       $not = Notification::create([
           'title' => $notification['title'],
           'body' => $notification['body'],
           'payload' => json_encode($notification['payload']),
       ]);
       if(is_array($users)){
        foreach($users as $u){
            $id = User::where('doctor_id',$u)->where('notification_status',0)->pluck('id')->first();
                if(isset($id)){
                    $ids[] = $id;
                }else{
                    $ids =null;
                }
            }
       }else{
            $ids[] = User::where('id',$users)->where('notification_status',0)->pluck('id')->first();
       }

       if($ids != null){
         $not=  $not->users()->sync($ids);
       }

       // if users are set on request it will send notifications to selected users otherwise it will
       // send notifications to all users

    //    $ids = User::whereHas('userDetail',function ($qb){
    //        $qb->where('notification_status',1);
    //    })->when(isset($users), function ($qb) use($users) {
    //        $qb->whereIn('id',$users);
    //    })->pluck('id')->toArray();
    if ($ids != null) {
        foreach ($ids as $id) {
            $token = FCMDevice::where('user_id', $id)->pluck('token')->first();
            if (isset($token)) {
                $tokens[] = $token;
            } else {
                $tokens[] ='';
            }
        }
        if (count($tokens)>0) {

            $service = new PushNotificationService();
            $data = $service->send($tokens, $notification);
            return $data;
        } else {
            $data = null;
            return $data;
        }
    }
    $data = null;
    return $data;
    }

}
