<?php

namespace App\Http\Controllers\DoctorApiControllers;

use App\FCMDevice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\DoctorApiResource\NotificationResource;
use App\User;
use Illuminate\Support\Facades\DB;

class PushNotificationController extends Controller {

    public function index()
    {

        $user_id = Auth::user()->id;

        $notifications    =   DB::table('notifications as nt')
                                ->join('user_notification as un','nt.id','un.notification_id')
                                ->where('un.user_id',$user_id)
                                ->orderBy('nt.created_at',"DESC")
                                ->select('nt.id','nt.title as title','nt.body as body','nt.created_at')
                                ->take(15)
                                ->get();
        return NotificationResource::collection($notifications);
    }
    public function register(Request $request){

        $input = $request->all();
        $user_id = Auth::user()->id;

        $validate = $request->validate([
            'token'              => 'required',
        ]);

        $device = FCMDevice::where([
            ['user_id',$user_id],
            ['token',$input['token']],
        ])->first();

        if(!$device){
            FCMDevice::create([
                'user_id' => $user_id,
                'token' => $input['token'],
            ]);
        }
        return [
            'message' => 'Device Registered Successfully'
        ];
    }
    public function destroy(Request $request,$id){
        $notification = DB::table('notifications')->where('id',$id)->delete();
        $user_notification = DB::table('user_notification')->where('notification_id',$id)->delete();
        if($user_notification){
            return response()->json(['data' => "Notificaton deleted successfully"], 200);
        }else{
            return response()->json(['data' => "There is no notification for delete"], 404);
        }
    }
    public function destroy_all(){
        $user_id = Auth::user()->id;
        $user_notification = DB::table('user_notification')->where('user_id',$user_id)->select('notification_id')->get();

        foreach($user_notification as $un){
            $notification = DB::table('notifications')->where('id',$un->notification_id)->delete();
        }
        $user_notification_delete = DB::table('user_notification')->where('user_id',$user_id)->delete();
        if($user_notification_delete){
            return response()->json(['data' => "Notificaton deleted successfully"], 200);
        }else{
        return response()->json(['data' => "There no notification for delete"], 404);
        }
    }

}
