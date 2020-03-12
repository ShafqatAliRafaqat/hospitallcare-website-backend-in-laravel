<?php

namespace App\Http\Controllers\AdminControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Twilio\Jwt\ClientToken;
use Twilio\Rest\Client;
class SmsController extends Controller
{
    public function sendSms()
    {

    $account_sid = 'ACd70dc80a1e021a4992687e81ce5f2b9d';
    $auth_token = 'dad166a659bd32c62d4ee779f8510405';

    $client = new Client($account_sid, $auth_token);

    $message = $client->messages->create(
        '+923408572757', // Text this number
      array(
        'from' => '+12024100223', // From a valid Twilio number
        'body' => 'Hello from Twilio!',
        dd($client)
      )
    );

    print $message->sid;
    }
}
